document.addEventListener('DOMContentLoaded', () => {
    // Configuration settings for selectors and endpoints
    const config = {
      selectAll: '#select-all',
      deleteButton: '#delete-selected',
      checkboxSelector: '.file-checkbox',
      accessTokenUrl: '/dropbox/access-token',
      deleteUrl: '/dashboard/dropbox/files'
    };
  
    class DeleteHandler {
      constructor() {
        this.selectAll = document.querySelector(config.selectAll);
        this.deleteBtn = document.querySelector(config.deleteButton);
        this.checkboxes = Array.from(document.querySelectorAll(config.checkboxSelector));
        this.bindEvents();
        this.updateSelection();
      }
  
      bindEvents() {
        // "Select All" checkbox change event
        if (this.selectAll) {
          this.selectAll.addEventListener('change', (e) => {
            this.checkboxes.forEach(checkbox => {
              checkbox.checked = e.target.checked;
            });
            this.updateSelection();
          });
        }
  
        // Listen for changes on any file checkbox
        document.addEventListener('change', (e) => {
          if (e.target.matches(config.checkboxSelector)) {
            this.updateSelection();
          }
        });
  
        // Delete button click event
        if (this.deleteBtn) {
          this.deleteBtn.addEventListener('click', () => this.handleDelete());
        }
      }
  
      updateSelection() {
        // Refresh the list of file checkboxes
        this.checkboxes = Array.from(document.querySelectorAll(config.checkboxSelector));
        const checkedBoxes = this.checkboxes.filter(checkbox => checkbox.checked);
  
        // Update "select all" checkbox state
        if (this.selectAll) {
          this.selectAll.checked =
            this.checkboxes.length > 0 && this.checkboxes.every(cb => cb.checked);
          this.selectAll.indeterminate =
            checkedBoxes.length > 0 && checkedBoxes.length < this.checkboxes.length;
        }
  
        // Enable/disable the delete button based on selection
        if (this.deleteBtn) {
          this.deleteBtn.disabled = checkedBoxes.length === 0;
        }
      }
  
      /**
       * Retrieve an access token for the given account.
       * @param {string} accountId
       * @returns {Promise<string>} The access token.
       */
      async getAccessToken(accountId) {
        const response = await fetch(`${config.accessTokenUrl}?account_id=${accountId}`);
        if (!response.ok) {
          throw new Error('Failed to retrieve access token.');
        }
        const data = await response.json();
        return data.access_token;
      }
  
      /**
       * Delete a file from Dropbox.
       * @param {string} filePath
       * @param {string} accessToken
       */
      async deleteFileFromDropbox(filePath, accessToken) {
        const response = await fetch('https://api.dropboxapi.com/2/files/delete_v2', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${accessToken}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ path: filePath })
        });
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.error_summary || 'Dropbox file deletion failed');
        }
      }
  
      /**
       * Delete a file record from the database.
       * @param {string} fileId
       */
      async deleteFileFromDB(fileId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const response = await fetch(`${config.deleteUrl}/${fileId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
          }
        });
        if (!response.ok) {
          throw new Error('Database deletion failed');
        }
      }
  
      /**
       * List the contents of a Dropbox folder.
       * @param {string} folderPath
       * @param {string} accessToken
       * @returns {Promise<Object>} The JSON response from Dropbox.
       */
      async listFolderContents(folderPath, accessToken) {
        const response = await fetch('https://api.dropboxapi.com/2/files/list_folder', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${accessToken}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ path: folderPath })
        });
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.error_summary || 'Failed to list folder');
        }
        return await response.json();
      }
  
      /**
       * Delete a Dropbox folder.
       * @param {string} folderPath
       * @param {string} accessToken
       */
      async deleteFolder(folderPath, accessToken) {
        const response = await fetch('https://api.dropboxapi.com/2/files/delete_v2', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${accessToken}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ path: folderPath })
        });
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.error_summary || 'Folder deletion failed');
        }
      }
  
      /**
       * Given a file path, return an array of its parent folder paths.
       * For example, given:
       *   "/معادلات تفاضلية (1)/اخلاقيات/file.pdf"
       * returns:
       *   ["/معادلات تفاضلية (1)/اخلاقيات", "/معادلات تفاضلية (1)"]
       * @param {string} filePath
       * @returns {Array<string>}
       */
      getParentFolders(filePath) {
        // Remove trailing slashes
        filePath = filePath.replace(/\/+$/, '');
        // Ensure the file path starts with a '/'
        if (!filePath.startsWith('/')) {
          filePath = '/' + filePath;
        }
        const parts = filePath.split('/').filter(Boolean); // Remove empty parts
        const folders = [];
        // Build parent folder paths from innermost to outermost
        for (let i = parts.length - 1; i > 0; i--) {
          const folder = '/' + parts.slice(0, i).join('/');
          folders.push(folder);
        }
        return folders;
      }
  
      /**
       * Checks if a folder is empty and deletes it if so.
       * @param {string} folderPath
       * @param {string} accountId
       * @returns {Promise<boolean>} True if the folder was deleted, false otherwise.
       */
      async deleteFolderIfEmpty(folderPath, accountId) {
        const accessToken = await this.getAccessToken(accountId);
        const listData = await this.listFolderContents(folderPath, accessToken);
        if (listData.entries.length === 0) {
          await this.deleteFolder(folderPath, accessToken);
          return true;
        }
        return false;
      }
  
      /**
       * Process deletion of selected files and then check (and delete) their parent folders if empty.
       */
      async handleDelete() {
        const selectedFiles = this.checkboxes
          .filter(checkbox => checkbox.checked)
          .map(checkbox => ({
            fileId: checkbox.value,
            accountId: checkbox.dataset.accountId,
            filePath: checkbox.dataset.filePath
          }));
  
        if (selectedFiles.length === 0) return;
        if (!confirm('Are you sure you want to permanently delete the selected files?')) return;
  
        const fileResults = [];
        // Use a Map to store unique folder paths across all files.
        // The key is the folder path and the value is the associated accountId.
        const allParentFolders = new Map();
  
        // Process each selected file
        for (const file of selectedFiles) {
          try {
            const accessToken = await this.getAccessToken(file.accountId);
            // Delete the file from Dropbox
            await this.deleteFileFromDropbox(file.filePath, accessToken);
            // Delete the file record from the database
            await this.deleteFileFromDB(file.fileId);
            fileResults.push({ fileId: file.fileId, success: true });
            // Get all parent folders for this file and add them to the Map
            const parentFolders = this.getParentFolders(file.filePath);
            parentFolders.forEach(folder => {
              allParentFolders.set(folder, file.accountId);
            });
          } catch (error) {
            console.error(`Error deleting file ${file.fileId}:`, error);
            fileResults.push({ fileId: file.fileId, success: false, error: error.message });
          }
        }
  
        // Process parent folders in descending order of depth (deepest folder first)
        const folderPaths = Array.from(allParentFolders.keys());
        folderPaths.sort((a, b) => b.split('/').length - a.split('/').length);
  
        const folderResults = [];
        for (const folderPath of folderPaths) {
          const accountId = allParentFolders.get(folderPath);
          try {
            const deleted = await this.deleteFolderIfEmpty(folderPath, accountId);
            folderResults.push({
              folderPath,
              success: deleted,
              message: deleted ? 'Folder deleted' : 'Folder not empty'
            });
          } catch (error) {
            console.error(`Error deleting folder ${folderPath}:`, error);
            folderResults.push({ folderPath, success: false, error: error.message });
          }
        }
  
        this.handleResults(fileResults, folderResults);
      }
  
      /**
       * Display a summary of file and folder deletion results, then reload the page.
       * @param {Array<Object>} fileResults
       * @param {Array<Object>} folderResults
       */
      handleResults(fileResults, folderResults) {
        let message = '';
  
        const failedFiles = fileResults.filter(r => !r.success);
        if (failedFiles.length === 0) {
          message += 'All selected files were deleted successfully!\n';
        } else {
          message += 'Some files could not be deleted:\n';
          message += failedFiles.map(r => `File ${r.fileId}: ${r.error}`).join('\n') + '\n';
        }
  
        const failedFolders = folderResults.filter(r => !r.success && r.error);
        if (failedFolders.length > 0) {
          message += 'Some folders could not be deleted:\n';
          message += failedFolders.map(r => `Folder ${r.folderPath}: ${r.error}`).join('\n') + '\n';
        }
  
        alert(message);
        window.location.reload();
      }
    }
  
    // Initialize the deletion handler
    new DeleteHandler();
  });
  