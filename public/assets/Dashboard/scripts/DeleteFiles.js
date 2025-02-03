document.addEventListener('DOMContentLoaded', function() {
    // Configuration
    const config = {
        selectAll: '#select-all',
        deleteButton: '#delete-selected',
        checkboxSelector: '.file-checkbox',
        accessTokenUrl: '/dropbox/access-token',
        deleteUrl: '/dashboard/dropbox/files'
    };

    // Initialize handler
    const deleteHandler = {
        init: function() {
            this.selectAll = document.querySelector(config.selectAll);
            this.deleteBtn = document.querySelector(config.deleteButton);
            this.checkboxes = [];
            this.bindEvents();
            this.updateSelection();
        },

        bindEvents: function() {
            // Select All functionality
            if (this.selectAll) {
                this.selectAll.addEventListener('change', (e) => {
                    this.checkboxes.forEach(c => c.checked = e.target.checked);
                    this.updateSelection();
                });
            }

            // Individual checkbox handling
            document.addEventListener('change', (e) => {
                if (e.target.matches(config.checkboxSelector)) this.updateSelection();
            });

            // Delete button click
            if (this.deleteBtn) {
                this.deleteBtn.addEventListener('click', () => this.handleDelete());
            }
        },

        updateSelection: function() {
            this.checkboxes = Array.from(document.querySelectorAll(config.checkboxSelector));
            const checked = this.checkboxes.filter(c => c.checked);
            
            if (this.selectAll) {
                this.selectAll.checked = this.checkboxes.length > 0 && 
                                      this.checkboxes.every(c => c.checked);
                this.selectAll.indeterminate = checked.length > 0 && 
                                             checked.length < this.checkboxes.length;
            }
            
            if (this.deleteBtn) {
                this.deleteBtn.disabled = checked.length === 0;
            }
        },

        handleDelete: async function() {
            const selected = this.checkboxes
                .filter(c => c.checked)
                .map(c => ({
                    fileId: c.value,
                    accountId: c.dataset.accountId,
                    filePath: c.dataset.filePath
                }));

            if (!selected.length) return;

            if (!confirm('Are you sure you want to permanently delete the selected files?')) return;

            try {
                const results = [];
                
                for (const file of selected) {
                    try {
                        // Get access token
                        const tokenResponse = await fetch(`${config.accessTokenUrl}?account_id=${file.accountId}`);
                        if (!tokenResponse.ok) throw new Error('Failed to get access token');
                        const { access_token } = await tokenResponse.json();

                        // Delete from Dropbox
                        const dropboxResponse = await fetch('https://api.dropboxapi.com/2/files/delete_v2', {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${access_token}`,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ path: file.filePath })
                        });

                        if (!dropboxResponse.ok) {
                            const errorData = await dropboxResponse.json();
                            throw new Error(errorData.error_summary || 'Dropbox deletion failed');
                        }

                        // Delete from database
                        const dbResponse = await fetch(`${config.deleteUrl}/${file.fileId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        });

                        if (!dbResponse.ok) throw new Error('Database deletion failed');

                        results.push({ success: true, fileId: file.fileId });

                    } catch (error) {
                        console.error(`Error deleting file ${file.fileId}:`, error);
                        results.push({ 
                            success: false, 
                            fileId: file.fileId,
                            error: error.message 
                        });
                    }
                }

                this.handleResults(results);

            } catch (error) {
                console.error('Deletion error:', error);
                alert('An unexpected error occurred: ' + error.message);
            }
        },

        handleResults: function(results) {
            const failedDeletions = results.filter(r => !r.success);
            if (failedDeletions.length === 0) {
                alert('All selected files were deleted successfully!');
                window.location.reload();
            } else {
                const errorList = failedDeletions.map(f => `File ${f.fileId}: ${f.error}`).join('\n');
                alert(`Some files could not be deleted:\n${errorList}`);
            }
        }
    };

    // Initialize the handler
    deleteHandler.init();
});