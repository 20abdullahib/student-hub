@extends('dashboard.layout.layout')

@section('title', 'Dropbox Files')
@section('custom-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dropbox Controllers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Files</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Dropbox Uploaded Files</h1>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('dropbox.files.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="file_name" class="form-control" 
                               placeholder="File name" value="{{ request('file_name') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <select name="subject_name" class="form-control">
                            <option value="">All Subjects</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->name }}" 
                                    {{ request('subject_name') == $subject->name ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="department_name" class="form-control">
                            <option value="">All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->name }}" 
                                    {{ request('department_name') == $department->name ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('dropbox.files.index') }}" class="btn btn-secondary w-100 mt-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Files Table -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <button id="delete-selected" class="btn btn-danger" disabled>
                    <i class="fas fa-trash me-2"></i>Delete Selected
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0 rounded">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0 rounded-start" style="width: 30px;">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="border-0">#</th>
                            <th class="border-0">File Name</th>
                            <th class="border-0">Size</th>
                            <th class="border-0">Link</th>
                            <th class="border-0">Subject</th>
                            <th class="border-0">Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $file)
                            <tr>
                                <td>
                                    <input type="checkbox" 
                                           class="file-checkbox" 
                                           value="{{ $file->id }}"
                                           data-account-id="{{ $file->dropbox_account_id }}"
                                           data-file-path="{{ $file->path }}">
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $file->name }}</td>
                                <td>{{ formatBytes($file->size) }}</td>
                                <td>
                                    <a href="{{ $file->link }}" target="_blank" class="text-primary">
                                        <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Open
                                    </a>
                                </td>
                                <td>{{ $file->subject->name ?? 'N/A' }}</td>
                                <td>{{ $file->dropboxAccount->department->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No files found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $files->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')


<script src="{{ asset('assets/Dashboard/scripts/DeleteFiles.js') }}"></script>

{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const deleteBtn = document.getElementById('delete-selected');
    let checkboxes = [];

    // Update checkbox states and button visibility
    function updateSelection() {
        checkboxes = Array.from(document.querySelectorAll('.file-checkbox'));
        const checked = checkboxes.filter(c => c.checked);
        
        selectAll.checked = checkboxes.length > 0 && checkboxes.every(c => c.checked);
        selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
        deleteBtn.disabled = checked.length === 0;
    }

    // Select All functionality
    selectAll?.addEventListener('change', (e) => {
        checkboxes.forEach(c => c.checked = e.target.checked);
        updateSelection();
    });

    // Individual checkbox handling
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('file-checkbox')) updateSelection();
    });

    // Delete Selected Files
    deleteBtn.addEventListener('click', async () => {
        const selected = checkboxes
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
                    const tokenResponse = await fetch(`/dropbox/access-token?account_id=${file.accountId}`);
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
                    const dbResponse = await fetch(`/dashboard/dropbox/files/${file.fileId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
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

            // Handle results
            const failedDeletions = results.filter(r => !r.success);
            if (failedDeletions.length === 0) {
                alert('All selected files were deleted successfully!');
                window.location.reload();
            } else {
                const errorList = failedDeletions.map(f => `File ${f.fileId}: ${f.error}`).join('\n');
                alert(`Some files could not be deleted:\n${errorList}`);
            }

        } catch (error) {
            console.error('Deletion error:', error);
            alert('An unexpected error occurred: ' + error.message);
        }
    });

    // Initial setup
    updateSelection();
});
</script> --}}
@endsection

@php
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
}
@endphp