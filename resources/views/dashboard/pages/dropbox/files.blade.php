@extends('dashboard.layout.layout')

@section('title', 'Dropbox Files')

@section('content')
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
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
                <!-- File Name Filter -->
                <div class="col-md-3">
                    <input type="text" name="file_name" class="form-control" 
                           placeholder="File name" 
                           value="{{ request('file_name') }}">
                </div>

                <!-- Subject Filter -->
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

                <!-- Department Filter -->
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

                <!-- Action Buttons -->
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
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0 rounded-start">#</th>
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
                            <td colspan="6" class="text-center py-4">No files found</td>
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

@php
    // Helper function to format bytes (add this in your helpers file or at the bottom of the view)
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
    }
@endphp
       
