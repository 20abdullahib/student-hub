<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files and Folders</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-4">
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <!-- Subject Selection -->
            <div class="mb-3">
                <label for="subject" class="form-label">Select Subject</label>
                <select id="subject" name="subject_id" class="form-select" required>
                    <option value="" disabled selected>Select a subject</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- File Input Button -->
            <div class="mb-3">
                <label for="fileInput" class="form-label">Select Files and Folders</label>
                <input type="file" id="fileInput" name="files[]" multiple webkitdirectory class="form-control">
            </div>

            <!-- File List -->
            <div id="fileList" class="mt-3"></div>

            <!-- Submit Button -->
            <button type="button" id="uploadButton" class="btn btn-primary mt-3">
                Upload Files
            </button>
            <!-- Reset Button -->
            <button type="button" id="resetButton" class="btn btn-secondary mt-3">
                Reset
            </button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/dropbox/dist/Dropbox-sdk.min.js"></script>
    <script>
        let CLIENT = null;

        // Upload a single file to Dropbox
        function uploadFiles(files = null) {
            if (!CLIENT) {
                alert('Please set the Access Token first');
                return;
            }

            const fileInput = files || document.getElementById('fileInput').files;
            const subjectSelect = document.getElementById('subject');
            const subjectTitle = subjectSelect.options[subjectSelect.selectedIndex].text;

            if (fileInput.length === 0) {
                alert('Please select a file to upload');
                return;
            }

            for (let file of fileInput) {
                const fileName = file.webkitRelativePath || file.name;
                const filePath = `/${subjectTitle}/${fileName}`;

                CLIENT.filesUpload({ path: filePath, contents: file })
                    .then(() => {
                        console.log(`File ${fileName} uploaded successfully`);
                    })
                    .catch(error => {
                        console.error(`Error uploading file ${fileName}:`, error);
                        if (error.status === 401) {
                            alert('Session expired. Please re-authenticate.');
                            // Optionally, you can redirect the user to the login page or refresh the token
                        }
                    })
                    .finally(() => {
                        console.log(`Finished uploading file: ${fileName}`);
                    });
            }
        }

        // Upload all files from a selected folder to Dropbox
        function uploadFolder(files = null) {
            if (!CLIENT) {
                alert('Please set the Access Token first');
                return;
            }

            const folderFiles = files || document.getElementById('fileInput').files;
            const subjectSelect = document.getElementById('subject');
            const subjectTitle = subjectSelect.options[subjectSelect.selectedIndex].text;

            if (folderFiles.length === 0) {
                alert('Please select a folder to upload');
                return;
            }

            for (let file of folderFiles) {
                const fileName = file.webkitRelativePath || file.name;
                const filePath = `/${subjectTitle}/${fileName}`;

                CLIENT.filesUpload({ path: filePath, contents: file })
                    .then(() => {
                        console.log(`File ${fileName} uploaded successfully`);
                    })
                    .catch(error => {
                        console.error(`Error uploading file ${fileName}:`, error);
                        if (error.status === 401) {
                            alert('Session expired. Please re-authenticate.');
                            // Optionally, you can redirect the user to the login page or refresh the token
                        }
                    })
                    .finally(() => {
                        console.log(`Finished uploading file: ${fileName}`);
                    });
            }
        }

        // Remove file from the queue
        function removeFileFromQueue(fileName) {
            const fileItem = document.getElementById(`fileItem-${fileName}`);
            if (fileItem) {
                fileItem.remove();
            }
        }

        // Update progress bar
        function updateProgressBar(fileName, percentage) {
            const progressBar = document.getElementById(`progressBar-${fileName}`);
            if (progressBar) {
                progressBar.style.width = `${percentage}%`;
                progressBar.setAttribute('aria-valuenow', percentage);
                progressBar.innerText = `${percentage}%`;
            }
        }

        // Display file list with progress bars
        function displayFileList(files) {
            const fileList = document.getElementById('fileList');
            fileList.innerHTML = '';
            for (const file of files) {
                const fileName = file.webkitRelativePath || file.name;
                const listItem = document.createElement('div');
                listItem.id = `fileItem-${fileName}`;
                listItem.innerHTML = `
                    <div>${fileName}</div>
                    <div class="progress mt-1">
                        <div id="progressBar-${fileName}" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                `;
                fileList.appendChild(listItem);
            }
        }

        // Hide progress bar
        function hideProgressBar(fileName) {
            setTimeout(() => {
                const progressBar = document.getElementById(`progressBar-${fileName}`);
                if (progressBar) {
                    progressBar.parentElement.style.display = 'none';
                }
                removeFileFromQueue(fileName);
            }, 5000);
        }

        // Upload large files using Dropbox's chunked upload API
        async function uploadLargeFile(file) {
            const CHUNK_SIZE = 8 * 1024 * 1024; // 8MB chunk size
            const fileSize = file.size;
            let offset = 0;
            const fileName = file.webkitRelativePath || file.name;
            const subjectSelect = document.getElementById('subject');
            const subjectTitle = subjectSelect.options[subjectSelect.selectedIndex].text;
            const filePath = `/${subjectTitle}/${fileName}`;

            console.log(`Starting upload for file: ${fileName} (${fileSize} bytes)`);

            try {
                // Start upload session
                const sessionStartResult = await CLIENT.filesUploadSessionStart({
                    close: false,
                    contents: file.slice(offset, offset + CHUNK_SIZE)
                });
                let sessionId = sessionStartResult.session_id;
                offset += CHUNK_SIZE;

                console.log(`Session started: ${sessionId}`);

                // Append chunks
                while (offset < fileSize) {
                    const chunk = file.slice(offset, offset + CHUNK_SIZE);
                    await CLIENT.filesUploadSessionAppendV2({
                        cursor: { session_id: sessionId, offset: offset },
                        contents: chunk
                    });
                    offset += CHUNK_SIZE;
                    console.log(`Uploaded chunk up to byte ${offset}`);
                    updateProgressBar(fileName, Math.min((offset / fileSize) * 100, 100));
                }

                // Finish upload session
                const commitInfo = {
                    path: filePath,
                    mode: 'add',
                    autorename: true,
                    mute: false
                };

                const result = await CLIENT.filesUploadSessionFinish({
                    cursor: { session_id: sessionId, offset: fileSize },
                    commit: commitInfo
                });

                console.log(`Upload complete: ${filePath}`, result);
                updateProgressBar(fileName, 100);
                hideProgressBar(fileName);
            } catch (error) {
                console.error(`Error uploading file ${fileName}:`, error);
                if (error.status === 401) {
                    alert('Session expired. Please re-authenticate.');
                    // Optionally, you can redirect the user to the login page or refresh the token
                }
            }
        }

        async function getAccessToken() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('dropbox.accessToken') }}',
                    method: 'GET',
                    success: function(response) {
                        const accessToken = response.access_token;
                        // console.log('Fetched Access Token:', accessToken); // Log the access token
                        resolve(accessToken);
                    },
                    error: function(error) {
                        console.error('Error fetching access token:', error);
                        alert('Failed to fetch access token. Please try again.');
                        reject(null);
                    }
                });
            });
        }

        async function sendFileDetails(fileName, filePath, fileSize, subjectId, dropboxAccountId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('file.details.store') }}',
                    method: 'POST',
                    data: {
                        name: fileName,
                        path: filePath,
                        size: fileSize,
                        subject_id: subjectId,
                        dropbox_account_id: dropboxAccountId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('File details saved successfully:', response);
                        resolve(response);
                    },
                    error: function(error) {
                        console.error('Error saving file details:', error);
                        reject(error);
                    }
                });
            });
        }

        document.getElementById('uploadButton').addEventListener('click', async () => {
            const files = document.getElementById('fileInput').files;
            const accountId = 1; // Replace with the actual account ID

            if (files.length === 0) {
                alert('Please select a file to upload');
                return;
            }

            const accessToken = await getAccessToken();
            if (!accessToken) {
                return;
            }

            CLIENT = new Dropbox.Dropbox({ accessToken: accessToken });
            console.log('Dropbox Client Initialized'); // Log client initialization

            displayFileList(files);

            for (const file of files) {
                if (file.size > 150 * 1024 * 1024) {
                    await uploadLargeFile(file);
                } else {
                    // const fileName = file.webkitRelativePath || file.name;
                    const fileName = file.webkitRelativePath || file.name;
                    const subjectSelect = document.getElementById('subject');
                    const subjectTitle = subjectSelect.options[subjectSelect.selectedIndex].text;
                    const filePath = `/${subjectTitle}/${fileName}`;

                    CLIENT.filesUpload({ path: filePath, contents: file })
                        .then(() => {
                            console.log(`File ${fileName} uploaded successfully`);
                            updateProgressBar(fileName, 100);
                            hideProgressBar(fileName);
                            sendFileDetails(fileName, filePath, file.size, subjectSelect.value, accountId);
                        })
                        .catch(error => {
                            console.error(`Error uploading file ${fileName}:`, error);
                            if (error.status === 401) {
                                alert('Session expired. Please re-authenticate.');
                                // Optionally, you can redirect the user to the login page or refresh the token
                            }
                        })
                        .finally(() => {
                            console.log(`Finished uploading file: ${fileName}`);
                        });
                }
            }
        });
    </script>
</body>

</html>

