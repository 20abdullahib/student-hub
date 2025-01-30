// // Constants
// const RETRY_DELAY = 1000; // Delay between retries in milliseconds
// const CHUNK_SIZE = 8 * 1024 * 1024; // 8MB chunk size for large file uploads
// // const ACCOUNT_ID = 2; // Replace with the actual account ID

// // DOM Elements
// const uploadButton = document.getElementById("uploadButton");
// const fileInput = document.getElementById("fileInput");
// const subjectSelect = document.getElementById("subject");
// const fileList = document.getElementById("fileList");

// // Global Variables
// let CLIENT = null;
// const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// // Utility Functions
// const showAlert = (message) => alert(message);
// const logMessage = (message) => console.log(message);
// const logError = (message, error) => console.error(message, error);

// // Progress Bar Class
// class ProgressBar {
//     constructor(fileName) {
//         this.fileName = fileName;
//         this.progressBar = document.getElementById(`progressBar-${fileName}`);
//     }

//     update(percentage) {
//         if (this.progressBar) {
//             this.progressBar.style.width = `${percentage}%`;
//             this.progressBar.setAttribute('aria-valuenow', percentage);
//             this.progressBar.innerText = `${percentage}%`;
//         }
//     }

//     hide() {
//         setTimeout(() => {
//             if (this.progressBar) {
//                 this.progressBar.parentElement.style.display = 'none';
//             }
//             removeFileFromQueue(this.fileName);
//         }, 5000);
//     }
// }

// // File Upload Functions
// const uploadFileToDropbox = async (file, filePath) => {
//     try {
//         await CLIENT.filesUpload({ path: filePath, contents: file });
//         logMessage(`File ${file.name} uploaded successfully`);
//         return true;
//     } catch (error) {
//         logError(`Error uploading file ${file.name}:`, error);
//         if (error.status === 401) showAlert('Session expired. Please re-authenticate.');
//         return false;
//     }
// };

// const uploadLargeFileToDropbox = async (file, filePath) => {
//     let offset = 0;
//     const fileSize = file.size;

//     try {
//         const sessionStartResult = await CLIENT.filesUploadSessionStart({
//             close: false,
//             contents: file.slice(offset, offset + CHUNK_SIZE),
//         });
//         let sessionId = sessionStartResult.session_id;
//         offset += CHUNK_SIZE;

//         while (offset < fileSize) {
//             const chunk = file.slice(offset, offset + CHUNK_SIZE);
//             await CLIENT.filesUploadSessionAppendV2({
//                 cursor: { session_id: sessionId, offset: offset },
//                 contents: chunk,
//             });
//             offset += CHUNK_SIZE;
//             updateProgressBar(file.name, Math.min((offset / fileSize) * 100, 100));
//         }

//         await CLIENT.filesUploadSessionFinish({
//             cursor: { session_id: sessionId, offset: fileSize },
//             commit: { path: filePath, mode: 'add', autorename: true, mute: false },
//         });

//         updateProgressBar(file.name, 100);
//         hideProgressBar(file.name);
//     } catch (error) {
//         logError(`Error uploading large file ${file.name}:`, error);
//         if (error.status === 401) showAlert('Session expired. Please re-authenticate.');
//     }
// };

// // File Management Functions
// const removeFileFromQueue = (fileName) => {
//     const fileItem = document.getElementById(`fileItem-${fileName}`);
//     fileItem?.remove();
// };

// const displayFileList = (files) => {
//     fileList.innerHTML = Array.from(files)
//         .map(
//             (file) => `
//         <div id="fileItem-${file.name}">
//             <div>${file.name}</div>
//             <div class="progress mt-1">
//                 <div id="progressBar-${file.name}" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
//             </div>
//         </div>
//     `
//         )
//         .join('');
// };

// // API Interaction Functions
// const getAccessToken = async () => {
//     const url = '/dropbox/access-token';

//     while (true) {
//         try {
//             if (!navigator.onLine) throw new Error('No internet connection. Please check your network.');

//             const response = await fetch(url, {
//                 method: 'GET',
//                 headers: { Accept: 'application/json' },
//             });

//             if (!response.ok) {
//                 if (response.status === 500) throw new Error('Server error. Please try again later.');
//                 if (response.status === 401) throw new Error('Unauthorized. Please re-authenticate.');
//                 throw new Error(`Failed to fetch access token: ${response.statusText}`);
//             }

//             const data = await response.json();
//             return data.access_token;
//         } catch (error) {
//             logError('Failed to fetch access token:', error);
//             showAlert(`Failed to fetch access token. Retrying... ${error.message}`);
//             await new Promise((resolve) => setTimeout(resolve, RETRY_DELAY));
//         }
//     }
// };

// const getSharedLink = async (accessToken, filePath) => {
//     const url = 'https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings';
//     try {
//         const response = await fetch(url, {
//             method: 'POST',
//             headers: {
//                 Authorization: `Bearer ${accessToken}`,
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({ path: filePath, settings: { requested_visibility: 'public' } }),
//         });

//         if (!response.ok) {
//             if (response.status === 409) return await getExistingSharedLink(accessToken, filePath);
//             throw new Error(`Failed to generate shared link: ${response.statusText}`);
//         }

//         return await response.json();
//     } catch (error) {
//         logError('Error generating shared link:', error);
//         throw error;
//     }
// };

// const getExistingSharedLink = async (accessToken, filePath) => {
//     const url = 'https://api.dropboxapi.com/2/sharing/list_shared_links';
//     try {
//         const response = await fetch(url, {
//             method: 'POST',
//             headers: {
//                 Authorization: `Bearer ${accessToken}`,
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({ path: filePath, direct_only: true }),
//         });

//         if (!response.ok) throw new Error(`Failed to fetch existing shared link: ${response.statusText}`);
//         const data = await response.json();
//         if (data.links.length > 0) return data.links[0];
//         throw new Error('No existing shared link found');
//     } catch (error) {
//         logError('Error fetching existing shared link:', error);
//         throw error;
//     }
// };

// const sendFileDetails = async (fileName, filePath, fileSize, subjectId, dropboxAccountId, sharedLink, fileId) => {
//     try {
//         const response = await fetch('/dashboard/dropbox/file-details', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 Accept: 'application/json',
//                 'X-CSRF-TOKEN': csrfToken,
//             },
//             body: JSON.stringify({
//                 name: fileName,
//                 path: filePath,
//                 size: fileSize,
//                 subject_id: subjectId,
//                 dropbox_account_id: dropboxAccountId,
//                 link: sharedLink,
//                 file_id: fileId,
//             }),
//         });

//         if (!response.ok) throw new Error('Failed to save file details');
//         const data = await response.json();
//         logMessage('File details saved successfully:', data);
//         return data;
//     } catch (error) {
//         logError('Error saving file details:', error);
//         throw error;
//     }
// };

// // Event Handlers
// // Add this function to fetch available accounts
// const getAvailableAccounts = async (subjectId) => {
//     try {
//         const response = await fetch(`/dashboard/dropbox/get-account-for-upload?subject_id=${subjectId}`, {
//             method: 'GET',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//             }
//         });

//         if (!response.ok) throw new Error('Failed to fetch accounts');
//         return await response.json();
//     } catch (error) {
//         logError('Error fetching accounts:', error);
//         showAlert('Failed to load available Dropbox accounts');
//         throw error;
//     }
// };

// // Modified upload handler
// uploadButton.addEventListener('click', async () => {
//     try {
//         const files = fileInput.files;
//         const subjectId = subjectSelect.value;

//         if (!files.length) {
//             showAlert('Please select a file to upload');
//             return;
//         }

//         // Fetch available accounts for this subject
//         const accounts = await getAvailableAccounts(subjectId);
//         console.log('Available Dropbox accounts:', accounts);
        

//         // Use first account temporarily (you'll want to implement selection logic later)
//         const uploadAccount = accounts[0];
//         console.log('Using account for upload:', uploadAccount);

//         // Rest of your existing upload flow
//         const accessToken = await getAccessToken();
//         CLIENT = new Dropbox.Dropbox({ accessToken });
//         displayFileList(files);

//         for (const file of files) {
//             const filePath = `/${subjectSelect.selectedOptions[0].text}/${file.name}`;
//             const progressBar = new ProgressBar(file.name);
            
//             // Use the account ID from the fetched account
//             await uploadFile(file, filePath, subjectId, uploadAccount.id, progressBar);
//         }
//     } catch (error) {
//         logError('Upload failed:', error);
//         showAlert('File upload failed. Please try again.');
//     }
// });


// // // Add this function to initialize the Dropbox client
// // const initializeDropboxClient = async () => {
// //     if (!CLIENT) {
// //         const accessToken = await getAccessToken();
// //         CLIENT = new Dropbox.Dropbox({ accessToken });
// //     }
// // };

// // const displayStorageUsage = async () => {
// //     try {
// //         // Initialize client if needed
// //         if (!CLIENT) {
// //             const accessToken = await getAccessToken();
// //             CLIENT = new Dropbox.Dropbox({ accessToken });
// //         }

// //         // Get storage data
// //         const response = await CLIENT.usersGetSpaceUsage();
// //         const { result } = response;
        
// //         if (!result?.allocation) {
// //             throw new Error('Invalid storage data structure from Dropbox');
// //         }

// //         // Extract values from response
// //         const usedBytes = result.used;
// //         const allocation = result.allocation;
// //         const allocatedBytes = allocation.allocated; // For individual accounts

// //         // Convert to GB
// //         const usedGB = (usedBytes / (1024 ** 3)).toFixed(2);
// //         const totalGB = (allocatedBytes / (1024 ** 3)).toFixed(2);
// //         const usedPercentage = ((usedBytes / allocatedBytes) * 100).toFixed(2);

// //         // Show result
// //         alert(`📦 Storage: ${usedGB}GB of ${totalGB}GB used (${usedPercentage}%)`);
// //         return { usedGB, totalGB, usedPercentage };

// //     } catch (error) {
// //         console.error('Storage check failed:', error);
// //         showAlert('Failed to check storage. Please ensure you\'re connected to Dropbox.');
// //         throw error;
// //     }
// // };


// const displayStorageUsage = async () => {
//     try {
//         // Initialize client if needed
//         if (!CLIENT) {
//             const accessToken = await getAccessToken();
//             CLIENT = new Dropbox.Dropbox({ accessToken });
//         }

//         // Get storage data
//         const response = await CLIENT.usersGetSpaceUsage();
//         const { result } = response;
        
//         if (!result?.allocation) {
//             throw new Error('Invalid storage data structure from Dropbox');
//         }

//         // Extract values from response
//         const usedBytes = result.used;
//         const allocation = result.allocation;
//         const allocatedBytes = allocation.allocated; // For individual accounts

//         // Convert to GB
//         const usedGB = (usedBytes / (1024 ** 3)).toFixed(2);
//         const totalGB = (allocatedBytes / (1024 ** 3)).toFixed(2);
//         const usedPercentage = ((usedBytes / allocatedBytes) * 100).toFixed(2);

//         // Show result
//         alert(`📦 Storage: ${usedGB}GB of ${totalGB}GB used (${usedPercentage}%)`);
//         return { usedGB, totalGB, usedPercentage };

//     } catch (error) {
//         console.error('Storage check failed:', error);
//         showAlert('Failed to check storage. Please ensure you\'re connected to Dropbox.');
//         throw error;
//     }
// };


// ======================================================

// // final code 1/29/2025

// // Constants
// const RETRY_DELAY = 1000;
// const CHUNK_SIZE = 8 * 1024 * 1024;

// // DOM Elements
// const uploadButton = document.getElementById("uploadButton");
// const fileInput = document.getElementById("fileInput");
// const subjectSelect = document.getElementById("subject");
// const fileList = document.getElementById("fileList");

// // Global Variables
// let CLIENT = null;
// const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// // Utility Functions
// const showAlert = (message) => alert(message);
// const logMessage = (message) => console.log(message);
// const logError = (message, error) => console.error(message, error);

// // Progress Bar Class
// class ProgressBar {
//     constructor(fileName) {
//         this.fileName = fileName;
//         this.progressBar = document.getElementById(`progressBar-${fileName}`);
//     }

//     update(percentage) {
//         if (this.progressBar) {
//             this.progressBar.style.width = `${percentage}%`;
//             this.progressBar.setAttribute('aria-valuenow', percentage);
//             this.progressBar.innerText = `${percentage}%`;
//         }
//     }

//     hide() {
//         setTimeout(() => {
//             if (this.progressBar) {
//                 this.progressBar.parentElement.style.display = 'none';
//             }
//             removeFileFromQueue(this.fileName);
//         }, 5000);
//     }
// }

// // File Upload Functions
// const uploadFileToDropbox = async (file, filePath) => {
//     try {
//         await CLIENT.filesUpload({ path: filePath, contents: file });
//         logMessage(`File ${file.name} uploaded successfully`);
//         return true;
//     } catch (error) {
//         logError(`Error uploading file ${file.name}:`, error);
//         if (error.status === 401) showAlert('Session expired. Please re-authenticate.');
//         return false;
//     }
// };

// const uploadLargeFileToDropbox = async (file, filePath) => {
//     let offset = 0;
//     const fileSize = file.size;

//     try {
//         const sessionStartResult = await CLIENT.filesUploadSessionStart({
//             close: false,
//             contents: file.slice(offset, offset + CHUNK_SIZE),
//         });
//         let sessionId = sessionStartResult.session_id;
//         offset += CHUNK_SIZE;

//         while (offset < fileSize) {
//             const chunk = file.slice(offset, offset + CHUNK_SIZE);
//             await CLIENT.filesUploadSessionAppendV2({
//                 cursor: { session_id: sessionId, offset: offset },
//                 contents: chunk,
//             });
//             offset += CHUNK_SIZE;
//             updateProgressBar(file.name, Math.min((offset / fileSize) * 100, 100));
//         }

//         await CLIENT.filesUploadSessionFinish({
//             cursor: { session_id: sessionId, offset: fileSize },
//             commit: { path: filePath, mode: 'add', autorename: true, mute: false },
//         });

//         updateProgressBar(file.name, 100);
//         hideProgressBar(file.name);
//     } catch (error) {
//         logError(`Error uploading large file ${file.name}:`, error);
//         if (error.status === 401) showAlert('Session expired. Please re-authenticate.');
//     }
// };

// // File Management Functions
// const removeFileFromQueue = (fileName) => {
//     const fileItem = document.getElementById(`fileItem-${fileName}`);
//     fileItem?.remove();
// };

// const displayFileList = (files) => {
//     fileList.innerHTML = Array.from(files)
//         .map(
//             (file) => `
//         <div id="fileItem-${file.name}">
//             <div>${file.name}</div>
//             <div class="progress mt-1">
//                 <div id="progressBar-${file.name}" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
//             </div>
//         </div>
//     `
//         )
//         .join('');
// };

// // API Interaction Functions
// const getAccessToken = async (accountId) => {
//     const url = `/dropbox/access-token?account_id=${accountId}`;

//     while (true) {
//         try {
//             if (!navigator.onLine) throw new Error('No internet connection. Please check your network.');

//             const response = await fetch(url, {
//                 method: 'GET',
//                 headers: { Accept: 'application/json' },
//             });

//             if (!response.ok) {
//                 if (response.status === 500) throw new Error('Server error. Please try again later.');
//                 if (response.status === 401) throw new Error('Unauthorized. Please re-authenticate.');
//                 throw new Error(`Failed to fetch access token: ${response.statusText}`);
//             }

//             const data = await response.json();
//             return data.access_token;
//         } catch (error) {
//             logError('Failed to fetch access token:', error);
//             showAlert(`Failed to fetch access token. Retrying... ${error.message}`);
//             await new Promise((resolve) => setTimeout(resolve, RETRY_DELAY));
//         }
//     }
// };

// const getSharedLink = async (accessToken, filePath) => {
//     const url = 'https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings';
//     try {
//         const response = await fetch(url, {
//             method: 'POST',
//             headers: {
//                 Authorization: `Bearer ${accessToken}`,
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({ path: filePath, settings: { requested_visibility: 'public' } }),
//         });

//         if (!response.ok) {
//             if (response.status === 409) return await getExistingSharedLink(accessToken, filePath);
//             throw new Error(`Failed to generate shared link: ${response.statusText}`);
//         }

//         return await response.json();
//     } catch (error) {
//         logError('Error generating shared link:', error);
//         throw error;
//     }
// };

// const getExistingSharedLink = async (accessToken, filePath) => {
//     const url = 'https://api.dropboxapi.com/2/sharing/list_shared_links';
//     try {
//         const response = await fetch(url, {
//             method: 'POST',
//             headers: {
//                 Authorization: `Bearer ${accessToken}`,
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({ path: filePath, direct_only: true }),
//         });

//         if (!response.ok) throw new Error(`Failed to fetch existing shared link: ${response.statusText}`);
//         const data = await response.json();
//         if (data.links.length > 0) return data.links[0];
//         throw new Error('No existing shared link found');
//     } catch (error) {
//         logError('Error fetching existing shared link:', error);
//         throw error;
//     }
// };

// const sendFileDetails = async (fileName, filePath, fileSize, subjectId, dropboxAccountId, sharedLink, fileId) => {
//     try {
//         const response = await fetch('/dashboard/dropbox/file-details', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 Accept: 'application/json',
//                 'X-CSRF-TOKEN': csrfToken,
//             },
//             body: JSON.stringify({
//                 name: fileName,
//                 path: filePath,
//                 size: fileSize,
//                 subject_id: subjectId,
//                 dropbox_account_id: dropboxAccountId,
//                 link: sharedLink,
//                 file_id: fileId,
//             }),
//         });

//         if (!response.ok) throw new Error('Failed to save file details');
//         const data = await response.json();
//         logMessage('File details saved successfully:', data);
//         return data;
//     } catch (error) {
//         logError('Error saving file details:', error);
//         throw error;
//     }
// };

// // Account Management Functions
// const getAvailableAccounts = async (subjectId) => {
//     try {
//         const response = await fetch(`/dashboard/dropbox/get-account-for-upload?subject_id=${subjectId}`, {
//             method: 'GET',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//             }
//         });

//         if (!response.ok) throw new Error('Failed to fetch accounts');
//         return await response.json();
//     } catch (error) {
//         logError('Error fetching accounts:', error);
//         showAlert('Failed to load available Dropbox accounts');
//         throw error;
//     }
// };

// const selectAccountWithSpace = async (accounts, fileSize) => {
//     try {
//         for (const account of accounts) {
//             try {
//                 const accessToken = await getAccessToken(account.id);
//                 CLIENT = new Dropbox.Dropbox({ accessToken });

//                 const response = await CLIENT.usersGetSpaceUsage();
//                 const { result } = response;

//                 if (!result?.allocation) {
//                     console.warn(`Skipping account ${account.id} - invalid storage data`);
//                     continue;
//                 }

//                 const availableSpace = result.allocation.allocated - result.used;
//                 console.log(`Account ${account.id} has ${availableSpace} bytes free`);

//                 if (availableSpace >= fileSize) {
//                     console.log(`Selected account ${account.id} for upload`);
//                     return account;
//                 }
//             } catch (error) {
//                 console.error(`Error checking account ${account.id}:`, error);
//                 continue;
//             }
//         }
//         return null;
//     } catch (error) {
//         console.error('Account selection failed:', error);
//         throw error;
//     }
// };

// // Progress Helpers
// const updateProgressBar = (fileName, percentage) => {
//     new ProgressBar(fileName).update(percentage);
// };

// const hideProgressBar = (fileName) => {
//     new ProgressBar(fileName).hide();
// };

// // Event Handlers
// uploadButton.addEventListener('click', async () => {
//     try {
//         const files = fileInput.files;
//         const subjectId = subjectSelect.value;

//         if (!files.length) {
//             showAlert('Please select a file to upload');
//             return;
//         }

//         // Get department-associated accounts
//         const accounts = await getAvailableAccounts(subjectId);
//         if (!accounts.length) {
//             showAlert('No Dropbox accounts available for upload');
//             return;
//         }

//         displayFileList(files);

//         for (const file of files) {
//             // Find suitable account
//             const uploadAccount = await selectAccountWithSpace(accounts, file.size);
//             if (!uploadAccount) {
//                 showAlert(`No space available for ${file.name} (${file.size} bytes). Please add new account.`);
//                 return;
//             }

//             // Initialize client with selected account
//             const accessToken = await getAccessToken(uploadAccount.id);
//             CLIENT = new Dropbox.Dropbox({ accessToken });

//             // Prepare upload path
//             const filePath = `/${subjectSelect.selectedOptions[0].text}/${file.name}`;
//             const progressBar = new ProgressBar(file.name);

//             // Execute upload
//             if (file.size > 150 * 1024 * 1024) {
//                 await uploadLargeFileToDropbox(file, filePath);
//             } else {
//                 const success = await uploadFileToDropbox(file, filePath);
//                 if (success) {
//                     const sharedLinkData = await getSharedLink(accessToken, filePath);
//                     const sharedLink = sharedLinkData.url;
//                     const fileId = sharedLink.split('/scl/fi/')[1].split('/')[0];
//                     await sendFileDetails(
//                         file.name,
//                         filePath,
//                         file.size,
//                         subjectId,
//                         uploadAccount.id,
//                         sharedLink,
//                         fileId
//                     );
//                 }
//             }

//             progressBar.update(100);
//             progressBar.hide();
//         }
//     } catch (error) {
//         logError('Upload failed:', error);
//         showAlert('File upload failed. Please try again.');
//     }
// });


/*
// TEST FUNCTION - VALIDATE ACCOUNT SELECTION LOGIC
// Add this commented section at the end of your file
const testAccountSpace = async () => {
    try {
        const TEST_SUBJECT_ID = 1; // Hardcoded for testing
        const TEST_FILE_SIZE = 500 * 1024 * 1024; // 500MB test file
        
        console.log('--- STARTING STORAGE TEST ---');
        
        const accounts = await getAvailableAccounts(TEST_SUBJECT_ID);
        console.log('Fetched accounts:', accounts);

        if (!accounts.length) {
            console.warn('No accounts available for testing');
            return;
        }

        for (const [index, account] of accounts.entries()) {
            try {
                const accessToken = await getAccessToken(account.id);
                const tempClient = new Dropbox.Dropbox({ accessToken });
                
                const storage = await tempClient.usersGetSpaceUsage();
                const result = storage.result;
                
                const available = result.allocation.allocated - result.used;
                const canUpload = available >= TEST_FILE_SIZE;
                
                console.log(`Account ${index + 1}: ${account.id}`);
                console.log(`- Total: ${(result.allocation.allocated / 1024 ** 3).toFixed(2)}GB`);
                console.log(`- Used: ${(result.used / 1024 ** 3).toFixed(2)}GB`);
                console.log(`- Available: ${(available / 1024 ** 3).toFixed(2)}GB`);
                console.log(`- Can upload 500MB: ${canUpload ? '✅' : '❌'}`);
                console.log('------------------------');
            } catch (error) {
                console.error(`Account ${index + 1} test failed:`, error.message);
            }
        }
        
        console.log('--- STORAGE TEST COMPLETE ---');
    } catch (error) {
        console.error('Storage test failed:', error);
    }
};

// Uncomment to run test
// testAccountSpace(); 
*/


/*
// // test for me
// const testAccountSpace = async () => {
//     try {
//         const TEST_SUBJECT_ID = 1; // Hardcoded for testing
//         const TEST_FILE_SIZE = 2000 * 1024 * 1024; // 500MB test file
        
//         console.log('--- STARTING STORAGE TEST ---');
        
//         const accounts = await getAvailableAccounts(TEST_SUBJECT_ID);
//         console.log('Fetched accounts:', accounts);

//         if (!accounts.length) {
//             console.warn('No accounts available for testing');
//             return;
//         }

//         for (const [index, account] of accounts.entries()) {
//             try {
//                 const accessToken = await getAccessToken(account.id);
//                 const tempClient = new Dropbox.Dropbox({ accessToken });
                
//                 const storage = await tempClient.usersGetSpaceUsage();
//                 const result = storage.result;
                
//                 const available = result.allocation.allocated - result.used;
//                 const canUpload = available >= TEST_FILE_SIZE;
                
//                 console.log(`Account ${index + 1}: ${account.id}`);
//                 console.log(`- Total: ${(result.allocation.allocated / 1024 ** 3).toFixed(2)}GB`);
//                 console.log(`- Used: ${(result.used / 1024 ** 3).toFixed(2)}GB`);
//                 console.log(`- Available: ${(available / 1024 ** 3)}GB`);
//                 console.log(`- Can upload ${TEST_FILE_SIZE / 1024 **3}: ${canUpload ? '✅' : '❌'}`);
//                 console.log('------------------------');
//             } catch (error) {
//                 console.error(`Account ${index + 1} test failed:`, error.message);
//             }
//         }
        
//         console.log('--- STORAGE TEST COMPLETE ---');
//     } catch (error) {
//         console.error('Storage test failed:', error);
//     }
// };
*/

// =================================================================





// // Constants
// const RETRY_DELAY = 1000;
// const CHUNK_SIZE = 8 * 1024 * 1024;
// const LARGE_FILE_THRESHOLD = 150 * 1024 * 1024;

// // DOM Elements
// const domElements = {
//   uploadButton: document.getElementById("uploadButton"),
//   fileInput: document.getElementById("fileInput"),
//   subjectSelect: document.getElementById("subject"),
//   fileList: document.getElementById("fileList"),
//   resetButton: document.getElementById("resetButton"),
//   csrfToken: document.querySelector('meta[name="csrf-token"]').content
// };

// // Dropbox Service
// const DropboxService = {
//   client: null,

//   initializeClient(accessToken) {
//     this.client = new Dropbox.Dropbox({ accessToken });
//     return this.client;
//   },

//   async uploadFile(file, filePath) {
//     return this.client.filesUpload({ path: filePath, contents: file });
//   },

//   async uploadLargeFile(file, filePath, progressCallback) {
//     let offset = 0;
//     const fileSize = file.size;

//     const sessionStart = await this.client.filesUploadSessionStart({
//       close: false,
//       contents: file.slice(offset, offset + CHUNK_SIZE),
//     });
//     let sessionId = sessionStart.result.session_id;
//     offset += CHUNK_SIZE;

//     while (offset < fileSize) {
//       const chunk = file.slice(offset, offset + CHUNK_SIZE);
//       await this.client.filesUploadSessionAppendV2({
//         cursor: { session_id: sessionId, offset },
//         contents: chunk,
//       });
//       offset += CHUNK_SIZE;
//       progressCallback(Math.min((offset / fileSize) * 100, 100));
//     }

//     return this.client.filesUploadSessionFinish({
//       cursor: { session_id: sessionId, offset: fileSize },
//       commit: { path: filePath, mode: 'add', autorename: true, mute: false },
//     });
//   },

//   async createSharedLink(filePath) {
//     try {
//       const response = await this.client.sharingCreateSharedLinkWithSettings({
//         path: filePath,
//         settings: { requested_visibility: 'public' }
//       });
//       return response.result;
//     } catch (error) {
//       if (error.status === 409) {
//         const response = await this.client.sharingListSharedLinks({ 
//           path: filePath,
//           direct_only: true
//         });
//         if (response.result.links.length > 0) {
//           return response.result.links[0];
//         }
//         throw new Error('Shared link already exists but none found');
//       }
//       throw error;
//     }
//   }
// };

// // Network Service
// const NetworkService = {
//   async fetchWithRetry(url, options, retries = 3) {
//     for (let i = 0; i < retries; i++) {
//       try {
//         const response = await fetch(url, options);
//         if (response.ok) return response;
//         if (response.status === 401) throw new Error('Unauthorized');
//       } catch (error) {
//         if (i === retries - 1) throw error;
//         await new Promise(resolve => setTimeout(resolve, RETRY_DELAY));
//       }
//     }
//     throw new Error('Request failed after retries');
//   },

//   async getAccessToken(accountId) {
//     const response = await this.fetchWithRetry(
//       `/dropbox/access-token?account_id=${accountId}`,
//       { headers: { Accept: 'application/json' } }
//     );
//     return response.json().then(data => data.access_token);
//   },

//   async sendFileDetails(fileData) {
//     return this.fetchWithRetry('/dashboard/dropbox/file-details', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//         'X-CSRF-TOKEN': domElements.csrfToken
//       },
//       body: JSON.stringify(fileData)
//     });
//   },

//   async getAvailableAccounts(subjectId) {
//     const response = await this.fetchWithRetry(
//       `/dashboard/dropbox/get-account-for-upload?subject_id=${subjectId}`,
//       { headers: { 'X-CSRF-TOKEN': domElements.csrfToken } }
//     );
//     return response.json();
//   }
// };

// // Progress Manager
// const ProgressManager = {
//   sanitizeId(fileName) {
//     return fileName.replace(/[^a-z0-9]/gi, '_').toLowerCase();
//   },

//   createProgressBar(fileName) {
//     const safeId = this.sanitizeId(fileName);
//     const html = `
//       <div id="fileItem-${safeId}" class="file-item">
//         <div class="d-flex justify-content-between align-items-center">
//           <span class="filename">${fileName}</span>
//           <div class="spinner-border spinner-border-sm text-primary d-none" role="status">
//             <span class="visually-hidden">Loading...</span>
//           </div>
//         </div>
//         <div class="progress mt-2">
//           <div id="progressBar-${safeId}" 
//                class="progress-bar" 
//                role="progressbar" 
//                style="width: 0%"
//                aria-valuenow="0" 
//                aria-valuemin="0" 
//                aria-valuemax="100">
//             0%
//           </div>
//         </div>
//       </div>
//     `;
//     domElements.fileList.insertAdjacentHTML('beforeend', html);
//     return new ProgressBar(safeId, fileName);
//   }
// };

// class ProgressBar {
//   constructor(safeId, originalFileName) {
//     this.safeId = safeId;
//     this.originalFileName = originalFileName;
//     this.element = document.getElementById(`progressBar-${safeId}`);
//     this.spinner = document.querySelector(`#fileItem-${safeId} .spinner-border`);
//     this.wrapper = document.getElementById(`fileItem-${safeId}`);
//   }

//   update(percentage) {
//     if (this.element) {
//       const rounded = Math.round(percentage);
//       this.element.style.width = `${rounded}%`;
//       this.element.setAttribute('aria-valuenow', rounded);
//       this.element.textContent = `${rounded}%`;
      
//       if (rounded === 100) {
//         this.element.classList.add('bg-success');
//         this.spinner?.classList.add('d-none');
//       }
//     }
//   }

//   showSpinner() {
//     this.spinner?.classList.remove('d-none');
//     this.wrapper?.classList.add('uploading');
//   }

//   remove() {
//     this.wrapper?.remove();
//   }
// }

// // Upload Manager
// const UploadManager = {
//   async selectAccountWithSpace(accounts, requiredSize) {
//     for (const account of accounts) {
//       try {
//         const accessToken = await NetworkService.getAccessToken(account.id);
//         const client = DropboxService.initializeClient(accessToken);
//         const spaceUsage = await client.usersGetSpaceUsage();
        
//         if (spaceUsage.result.allocation.allocated - spaceUsage.result.used >= requiredSize) {
//           return { account, accessToken };
//         }
//       } catch (error) {
//         console.error(`Account ${account.id} check failed:`, error);
//       }
//     }
//     return null;
//   },

//   async processFileUpload(file, subjectId, subjectName, relativePath = '') {
//     const accounts = await NetworkService.getAvailableAccounts(subjectId);
//     if (!accounts.length) throw new Error('No available accounts');

//     const accountInfo = await this.selectAccountWithSpace(accounts, file.size);
//     if (!accountInfo) throw new Error('Insufficient space');

//     const { account, accessToken } = accountInfo;
//     const client = DropboxService.initializeClient(accessToken);
//     const cleanPath = relativePath.replace(/^\/|\/$/g, '');
//     const filePath = `/${subjectName}/${cleanPath ? cleanPath + '/' : ''}${file.name}`;
//     const progressBar = ProgressManager.createProgressBar(file.name);

//     try {
//       progressBar.showSpinner();
      
//       if (file.size > LARGE_FILE_THRESHOLD) {
//         await DropboxService.uploadLargeFile(file, filePath, p => progressBar.update(p));
//       } else {
//         await DropboxService.uploadFile(file, filePath);
//         progressBar.update(100);
//       }

//       const sharedLink = await DropboxService.createSharedLink(filePath);
//       if (!sharedLink?.url) throw new Error('Failed to get shared link');
      
//       const fileId = sharedLink.url.split('/scl/fi/')[1]?.split('/')[0] || '';

//       await NetworkService.sendFileDetails({
//         name: file.name,
//         path: filePath,
//         size: file.size,
//         subject_id: subjectId,
//         dropbox_account_id: account.id,
//         link: sharedLink.url,
//         file_id: fileId
//       });

//     } catch (error) {
//       console.error(`Upload failed for ${file.name}:`, error);
//       progressBar.update(0);
//       throw error;
//     } finally {
//       setTimeout(() => progressBar.remove(), 5000);
//     }
//   }
// };

// // Event Handlers
// async function handleUpload() {
//   try {
//     const files = domElements.fileInput.files;
//     const subjectId = domElements.subjectSelect.value;
//     const subjectName = domElements.subjectSelect.selectedOptions[0]?.text;

//     if (!subjectId) {
//       alert('Please select a subject');
//       return;
//     }

//     if (!files.length) {
//       alert('Please select files or folders to upload');
//       return;
//     }

//     domElements.uploadButton.disabled = true;
    
//     for (const file of files) {
//       const relativePath = file.webkitRelativePath 
//         ? file.webkitRelativePath.split('/').slice(0, -1).join('/')
//         : '';
      
//       await UploadManager.processFileUpload(file, subjectId, subjectName, relativePath);
//     }

//     alert('All files uploaded successfully!');
//   } catch (error) {
//     console.error('Upload failed:', error);
//     alert(`Upload failed: ${error.message}`);
//   } finally {
//     domElements.uploadButton.disabled = false;
//   }
// }

// function resetForm() {
//   domElements.fileInput.value = '';
//   domElements.fileList.innerHTML = '';
//   domElements.subjectSelect.value = '';
// }

// // Initialize event listeners
// domElements.uploadButton.addEventListener('click', handleUpload);
// domElements.resetButton.addEventListener('click', resetForm);
// domElements.fileInput.addEventListener('change', () => {
//   domElements.fileList.innerHTML = '';
//   Array.from(domElements.fileInput.files).forEach(file => {
//     ProgressManager.createProgressBar(file.name);
//   });
// });


// ======================================================


// fantastic code

// // Constants
// const RETRY_DELAY = 1000;
// const CHUNK_SIZE = 8 * 1024 * 1024;
// const LARGE_FILE_THRESHOLD = 150 * 1024 * 1024;

// // DOM Elements
// const domElements = {
//   uploadButton: document.getElementById("uploadButton"),
//   fileInput: document.getElementById("fileInput"),
//   subjectSelect: document.getElementById("subject"),
//   fileList: document.getElementById("fileList"),
//   resetButton: document.getElementById("resetButton"),
//   csrfToken: document.querySelector('meta[name="csrf-token"]').content
// };

// // Dropbox Service (unchanged from previous working version)
// const DropboxService = {
//   client: null,
//   initializeClient(accessToken) {
//     this.client = new Dropbox.Dropbox({ accessToken });
//     return this.client;
//   },
//   async uploadFile(file, filePath) {
//     return this.client.filesUpload({ path: filePath, contents: file });
//   },
//   async uploadLargeFile(file, filePath) {
//     let offset = 0;
//     const fileSize = file.size;
//     const sessionStart = await this.client.filesUploadSessionStart({
//       close: false,
//       contents: file.slice(offset, offset + CHUNK_SIZE),
//     });
//     let sessionId = sessionStart.result.session_id;
//     offset += CHUNK_SIZE;
//     while (offset < fileSize) {
//       const chunk = file.slice(offset, offset + CHUNK_SIZE);
//       await this.client.filesUploadSessionAppendV2({
//         cursor: { session_id: sessionId, offset },
//         contents: chunk,
//       });
//       offset += CHUNK_SIZE;
//     }
//     return this.client.filesUploadSessionFinish({
//       cursor: { session_id: sessionId, offset: fileSize },
//       commit: { path: filePath, mode: 'add', autorename: true, mute: false },
//     });
//   },
//   async createSharedLink(filePath) {
//     try {
//       const response = await this.client.sharingCreateSharedLinkWithSettings({
//         path: filePath,
//         settings: { requested_visibility: 'public' }
//       });
//       return response.result;
//     } catch (error) {
//       if (error.status === 409) {
//         const response = await this.client.sharingListSharedLinks({ 
//           path: filePath,
//           direct_only: true
//         });
//         if (response.result.links.length > 0) {
//           return response.result.links[0];
//         }
//         throw new Error('Shared link already exists but none found');
//       }
//       throw error;
//     }
//   }
// };

// // Network Service (unchanged from previous working version)
// const NetworkService = {
//   async fetchWithRetry(url, options, retries = 3) {
//     for (let i = 0; i < retries; i++) {
//       try {
//         const response = await fetch(url, options);
//         if (response.ok) return response;
//         if (response.status === 401) throw new Error('Unauthorized');
//       } catch (error) {
//         if (i === retries - 1) throw error;
//         await new Promise(resolve => setTimeout(resolve, RETRY_DELAY));
//       }
//     }
//     throw new Error('Request failed after retries');
//   },
//   async getAccessToken(accountId) {
//     const response = await this.fetchWithRetry(
//       `/dropbox/access-token?account_id=${accountId}`,
//       { headers: { Accept: 'application/json' } }
//     );
//     return response.json().then(data => data.access_token);
//   },
//   async sendFileDetails(fileData) {
//     return this.fetchWithRetry('/dashboard/dropbox/file-details', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//         'X-CSRF-TOKEN': domElements.csrfToken
//       },
//       body: JSON.stringify(fileData)
//     });
//   },
//   async getAvailableAccounts(subjectId) {
//     const response = await this.fetchWithRetry(
//       `/dashboard/dropbox/get-account-for-upload?subject_id=${subjectId}`,
//       { headers: { 'X-CSRF-TOKEN': domElements.csrfToken } }
//     );
//     return response.json();
//   }
// };

// // File List Manager with duplicate prevention
// const FileListManager = {
//   fileMap: new Map(),

//   getFileId(file) {
//     return `${file.name}-${file.size}-${file.lastModified}`;
//   },

//   createFileItem(file) {
//     const fileId = this.getFileId(file);
    
//     if (this.fileMap.has(fileId)) {
//       return this.fileMap.get(fileId);
//     }

//     const item = document.createElement('div');
//     item.className = 'file-item';
//     item.innerHTML = `
//       <span class="filename">${file.name}</span>
//       <span class="status-text">Pending...</span>
//     `;
    
//     domElements.fileList.appendChild(item);
//     this.fileMap.set(fileId, item);
//     return item;
//   },

//   updateStatus(file, status) {
//     const fileId = this.getFileId(file);
//     const item = this.fileMap.get(fileId);
//     if (item) {
//       item.querySelector('.status-text').textContent = status;
//     }
//   },

//   removeFileItem(file) {
//     const fileId = this.getFileId(file);
//     const item = this.fileMap.get(fileId);
//     if (item) {
//       setTimeout(() => {
//         item.remove();
//         this.fileMap.delete(fileId);
//       }, 2000);
//     }
//   },

//   clearAll() {
//     this.fileMap.clear();
//     domElements.fileList.innerHTML = '';
//   }
// };

// // Upload Manager
// const UploadManager = {
//   async selectAccountWithSpace(accounts, requiredSize) {
//     for (const account of accounts) {
//       try {
//         const accessToken = await NetworkService.getAccessToken(account.id);
//         const client = DropboxService.initializeClient(accessToken);
//         const spaceUsage = await client.usersGetSpaceUsage();
        
//         if (spaceUsage.result.allocation.allocated - spaceUsage.result.used >= requiredSize) {
//           return { account, accessToken };
//         }
//       } catch (error) {
//         console.error(`Account ${account.id} check failed:`, error);
//       }
//     }
//     return null;
//   },

//   async processFileUpload(file, subjectId, subjectName, relativePath = '') {
//     const accounts = await NetworkService.getAvailableAccounts(subjectId);
//     if (!accounts.length) throw new Error('No available accounts');

//     const accountInfo = await this.selectAccountWithSpace(accounts, file.size);
//     if (!accountInfo) throw new Error('Insufficient space');

//     const { account, accessToken } = accountInfo;
//     const client = DropboxService.initializeClient(accessToken);
//     const cleanPath = relativePath.replace(/^\/|\/$/g, '');
//     const filePath = `/${subjectName}/${cleanPath ? cleanPath + '/' : ''}${file.name}`;

//     try {
//       FileListManager.updateStatus(file, 'Uploading...');
      
//       if (file.size > LARGE_FILE_THRESHOLD) {
//         await DropboxService.uploadLargeFile(file, filePath);
//       } else {
//         await DropboxService.uploadFile(file, filePath);
//       }

//       const sharedLink = await DropboxService.createSharedLink(filePath);
//       if (!sharedLink?.url) throw new Error('Failed to get shared link');
      
//       await NetworkService.sendFileDetails({
//         name: file.name,
//         path: filePath,
//         size: file.size,
//         subject_id: subjectId,
//         dropbox_account_id: account.id,
//         link: sharedLink.url,
//         file_id: sharedLink.url.split('/scl/fi/')[1]?.split('/')[0] || ''
//       });

//       FileListManager.updateStatus(file, 'Uploaded ✔️');

//     } catch (error) {
//       console.error(`Upload failed for ${file.name}:`, error);
//       FileListManager.updateStatus(file, 'Failed ❌');
//       throw error;
//     } finally {
//       FileListManager.removeFileItem(file);
//     }
//   }
// };

// // Event Handlers
// async function handleUpload() {
//   try {
//     const files = domElements.fileInput.files;
//     const subjectId = domElements.subjectSelect.value;
//     const subjectName = domElements.subjectSelect.selectedOptions[0]?.text;

//     if (!subjectId) {
//       alert('Please select a subject');
//       return;
//     }

//     if (!files.length) {
//       alert('Please select files or folders to upload');
//       return;
//     }

//     domElements.uploadButton.disabled = true;
    
//     for (const file of files) {
//       const relativePath = file.webkitRelativePath 
//         ? file.webkitRelativePath.split('/').slice(0, -1).join('/')
//         : '';
      
//       await UploadManager.processFileUpload(file, subjectId, subjectName, relativePath);
//     }

//   } catch (error) {
//     console.error('Upload failed:', error);
//     alert(`Upload failed: ${error.message}`);
//   } finally {
//     domElements.uploadButton.disabled = false;
//   }
// }

// function resetForm() {
//   domElements.fileInput.value = '';
//   FileListManager.clearAll();
//   domElements.subjectSelect.value = '';
// }

// // Initialize event listeners
// domElements.uploadButton.addEventListener('click', handleUpload);
// domElements.resetButton.addEventListener('click', resetForm);
// domElements.fileInput.addEventListener('change', () => {
//   FileListManager.clearAll();
//   Array.from(domElements.fileInput.files).forEach(file => {
//     FileListManager.createFileItem(file);
//   });
// });


// ======================================================


// Constants
const RETRY_DELAY = 1000;
const CHUNK_SIZE = 8 * 1024 * 1024;
const LARGE_FILE_THRESHOLD = 150 * 1024 * 1024;

// API Endpoints
const API_ENDPOINTS = {
  ACCESS_TOKEN: 'dropbox.api.token',
  REFRESH: 'dropbox.api.refresh',
  FILES: 'dropbox.api.files',
  STORE_DETAILS: 'dropbox.files.store',
  ACCOUNTS: 'dropbox.files.accounts',
  UPDATE_ACCOUNT: 'dropbox.account.update'
};

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
        route(API_ENDPOINTS.ACCESS_TOKEN, { account_id: accountId }),
        { headers: { Accept: 'application/json' } }
      );
      return response.json().then(data => data.access_token);
    },

    sendFileDetails: async (fileData) => {
      return fetchWithRetry(route(API_ENDPOINTS.STORE_DETAILS), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': domElements.csrfToken
        },
        body: JSON.stringify(fileData)
      });
    },

    updateAccountSpace: async (accountId, remainingSpace) => {
      return fetchWithRetry(route(API_ENDPOINTS.UPDATE_ACCOUNT), {
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
        route(API_ENDPOINTS.ACCOUNTS, { subject_id: subjectId }),
        { headers: { 'X-CSRF-TOKEN': domElements.csrfToken } }
      );
      return response.json();
    }
  };
})();

// Module: File List Manager (unchanged)
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

// Controller: Upload Management (unchanged)
const UploadController = (() => {
  const updateAccountSpace = async (client, accountId) => {
    try {
      const { allocated, used } = await DropboxService.getSpaceUsage();
      const remainingSpace = allocated - used;
      
      await NetworkService.updateAccountSpace(accountId, remainingSpace);
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

// Event Handlers (unchanged)
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

/* 
*************************************************************
***********  ADD BY abdullah.ibrahiim@yahoo.com *************
***********  https://20abdullah.serv00.net/ *****************
*************************************************************
*/ 