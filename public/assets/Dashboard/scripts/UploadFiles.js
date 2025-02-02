/* 
*************************************************************
***********  ADD BY abdullah.ibrahiim@yahoo.com *************
***********  https://20abdullah.serv00.net/ *****************
*************************************************************
*/ 

// Constants
const RETRY_DELAY = 1000;
const CHUNK_SIZE = 8 * 1024 * 1024;
const LARGE_FILE_THRESHOLD = 150 * 1024 * 1024;

// DOM Elements
const domElements = {
  uploadButton: document.getElementById("uploadButton"),
  fileInput: document.getElementById("fileInput"),
  subjectSelect: document.getElementById("subject"),
  fileList: document.getElementById("fileList"),
  resetButton: document.getElementById("resetButton"),
  csrfToken: document.querySelector('meta[name="csrf-token"]').content
};

// Service: Dropbox API Interactions
const DropboxService = (() => {
  let client = null;

  return {
    initializeClient: (accessToken) => {
      client = new Dropbox.Dropbox({ accessToken });
      return client;
    },

    uploadFile: async (file, filePath) => {
      return client.filesUpload({ path: filePath, contents: file });
    },

    uploadLargeFile: async (file, filePath) => {
      let offset = 0;
      const fileSize = file.size;
      
      const sessionStart = await client.filesUploadSessionStart({
        close: false,
        contents: file.slice(offset, offset + CHUNK_SIZE),
      });
      
      let sessionId = sessionStart.result.session_id;
      offset += CHUNK_SIZE;

      while (offset < fileSize) {
        const chunk = file.slice(offset, offset + CHUNK_SIZE);
        await client.filesUploadSessionAppendV2({
          cursor: { session_id: sessionId, offset },
          contents: chunk,
        });
        offset += CHUNK_SIZE;
      }

      return client.filesUploadSessionFinish({
        cursor: { session_id: sessionId, offset: fileSize },
        commit: { path: filePath, mode: 'add', autorename: true, mute: false },
      });
    },

    createSharedLink: async (filePath) => {
      try {
        const response = await client.sharingCreateSharedLinkWithSettings({
          path: filePath,
          settings: { requested_visibility: 'public' }
        });
        return response.result;
      } catch (error) {
        if (error.status === 409) {
          const response = await client.sharingListSharedLinks({ 
            path: filePath,
            direct_only: true
          });
          return response.result.links[0] || null;
        }
        throw error;
      }
    },

    getSpaceUsage: async () => {
      const response = await client.usersGetSpaceUsage();
      return {
        allocated: response.result.allocation.allocated,
        used: response.result.used
      };
    }
  };
})();

// Service: Network Requests
const NetworkService = (() => {
  const fetchWithRetry = async (url, options, retries = 3) => {
    for (let i = 0; i < retries; i++) {
      try {
        const response = await fetch(url, options);
        if (response.ok) return response;
        if (response.status === 401) throw new Error('Unauthorized');
      } catch (error) {
        if (i === retries - 1) throw error;
        await new Promise(resolve => setTimeout(resolve, RETRY_DELAY));
      }
    }
    throw new Error('Request failed after retries');
  };

  return {
    getAccessToken: async (accountId) => {
      const response = await fetchWithRetry(
        `/dropbox/access-token?account_id=${accountId}`,
        { headers: { Accept: 'application/json' } }
      );
      return response.json().then(data => data.access_token);
    },

    sendFileDetails: async (fileData) => {
      return fetchWithRetry('/dashboard/dropbox/files/store-details', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': domElements.csrfToken
        },
        body: JSON.stringify(fileData)
      });
    },

    updateAccountSpace: async (accountId, remainingSpace) => {
      return fetchWithRetry('/dashboard/dropbox/account/update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': domElements.csrfToken
        },
        body: JSON.stringify({
          account_id: accountId,
          remaining_storage: remainingSpace
        })
      });
    },

    getAvailableAccounts: async (subjectId) => {
      const response = await fetchWithRetry(
        `/dashboard/dropbox/files/accounts?subject_id=${subjectId}`,
        { headers: { 'X-CSRF-TOKEN': domElements.csrfToken } }
      );
      return response.json();
    }
  };
})();

// Module: File List Manager
const FileListManager = (() => {
  const fileMap = new Map();

  const getFileId = (file) => 
    `${file.name}-${file.size}-${file.lastModified}`;

  return {
    createFileItem: (file) => {
      const fileId = getFileId(file);
      if (fileMap.has(fileId)) return null;

      const item = document.createElement('div');
      item.className = 'file-item';
      item.innerHTML = `
        <span class="filename">${file.name}</span>
        <span class="status-text">Pending...</span>
      `;
      
      domElements.fileList.appendChild(item);
      fileMap.set(fileId, item);
      return item;
    },

    updateStatus: (file, status) => {
      const fileId = getFileId(file);
      const item = fileMap.get(fileId);
      if (item) {
        item.querySelector('.status-text').textContent = status;
      }
    },

    removeFileItem: (file) => {
      const fileId = getFileId(file);
      const item = fileMap.get(fileId);
      if (item) {
        setTimeout(() => {
          item.remove();
          fileMap.delete(fileId);
        }, 2000);
      }
    },

    clearAll: () => {
      fileMap.clear();
      domElements.fileList.innerHTML = '';
    }
  };
})();

// Controller: Upload Management
const UploadController = (() => {
  const updateAccountSpace = async (client, accountId) => {
    try {
      const { allocated, used } = await DropboxService.getSpaceUsage();
      const remainingSpace = allocated - used;
      
      await NetworkService.updateAccountSpace(accountId, remainingSpace);
    //   console.log(`Updated account ${accountId} remaining space to ${remainingSpace} bytes`);
    } catch (error) {
      console.error('Space update failed:', error);
    }
  };

  const selectAccountWithSpace = async (accounts, requiredSize) => {
    for (const account of accounts) {
      try {
        const accessToken = await NetworkService.getAccessToken(account.id);
        const client = DropboxService.initializeClient(accessToken);
        const { allocated, used } = await DropboxService.getSpaceUsage();
        
        if ((allocated - used) >= requiredSize) {
          return { account, accessToken };
        }
      } catch (error) {
        console.error(`Account check failed for ${account.id}:`, error);
      }
    }
    return null;
  };

  const processFileUpload = async (file, subjectId, subjectName, relativePath = '') => {
    const accounts = await NetworkService.getAvailableAccounts(subjectId);
    if (!accounts.length) throw new Error('No available accounts');

    const accountInfo = await selectAccountWithSpace(accounts, file.size);
    if (!accountInfo) throw new Error('Insufficient space');

    const { account, accessToken } = accountInfo;
    const client = DropboxService.initializeClient(accessToken);
    const cleanPath = relativePath.replace(/^\/|\/$/g, '');
    const filePath = `/${subjectName}/${cleanPath ? `${cleanPath}/` : ''}${file.name}`;

    try {
      FileListManager.updateStatus(file, 'Uploading...');
      
      await (file.size > LARGE_FILE_THRESHOLD
        ? DropboxService.uploadLargeFile(file, filePath)
        : DropboxService.uploadFile(file, filePath));

      const sharedLink = await DropboxService.createSharedLink(filePath);
      if (!sharedLink?.url) throw new Error('Failed to get shared link');
      
      await NetworkService.sendFileDetails({
        name: file.name,
        path: filePath,
        size: file.size,
        subject_id: subjectId,
        dropbox_account_id: account.id,
        link: sharedLink.url,
        file_id: sharedLink.url.split('/scl/fi/')[1]?.split('/')[0] || ''
      });

      await updateAccountSpace(client, account.id);
      FileListManager.updateStatus(file, 'Uploaded ✔️');
      return true;
    } catch (error) {
      console.error(`Upload failed for ${file.name}:`, error);
      FileListManager.updateStatus(file, 'Failed ❌');
      return false;
    } finally {
      FileListManager.removeFileItem(file);
    }
  };

  return { processFileUpload };
})();

// Event Handlers
const setupEventListeners = () => {
  const handleUpload = async () => {
    try {
      const files = domElements.fileInput.files;
      const subjectId = domElements.subjectSelect.value;
      const subjectName = domElements.subjectSelect.selectedOptions[0]?.text;

      if (!subjectId || !files.length) {
        alert(!subjectId ? 'Please select a subject' : 'Please select files');
        return;
      }

      domElements.uploadButton.disabled = true;
      let successCount = 0;
      let failureCount = 0;

      for (const file of files) {
        const relativePath = file.webkitRelativePath?.split('/').slice(0, -1).join('/') || '';
        const success = await UploadController.processFileUpload(
          file, subjectId, subjectName, relativePath
        );
        success ? successCount++ : failureCount++;
      }

      alert(`Uploads completed: ${successCount} successful, ${failureCount} failed`);

    } catch (error) {
      console.error('Upload process failed:', error);
      alert(`Upload process failed: ${error.message}`);
    } finally {
      domElements.uploadButton.disabled = false;
    }
  };

  const resetForm = () => {
    domElements.fileInput.value = '';
    domElements.subjectSelect.value = '';
    FileListManager.clearAll();
  };

  domElements.uploadButton.addEventListener('click', handleUpload);
  domElements.resetButton.addEventListener('click', resetForm);
  domElements.fileInput.addEventListener('change', () => {
    FileListManager.clearAll();
    Array.from(domElements.fileInput.files).forEach(FileListManager.createFileItem);
  });
};

// Initialize Application
setupEventListeners();