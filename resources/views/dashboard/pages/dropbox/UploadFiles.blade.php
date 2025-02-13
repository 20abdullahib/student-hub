@extends('dashboard.layout.layout')

@section('title', 'Upload Files')
@section('custom-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/Dashboard/css/custom-style-form.css') }}">
@endsection
@section('content')
    <!-- Page Header -->
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="#">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Upload Files</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Upload Files</h1>
                <p class="mb-0">Upload files and folders to the selected subject.</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <!-- Subject Selection -->
                        <div class="form-group mb-4">
                            <label for="subject" class="form-label">Select Subject</label>
                            <select id="subject" name="subject_id" class="form-select" required>
                                <option value="" disabled selected>Select a subject</option>
                                @if ($admin->role == 'admin' || $admin->role == 'super admin')
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                @else
                                    @foreach ($subjects as $subject)
                                        @if ($subject->department_id == $admin->department_id)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- File Input Button -->
                        <div class="form-group mb-4">
                            <label for="fileInput" class="form-label">Select Files and Folders</label>
                            <input type="file" id="fileInput" name="files[]" multiple directory webkitdirectory
                                class="form-control">
                        </div>

                        <!-- File List -->
                        <div id="fileList" class="mt-3"></div>

                        <!-- Submit and Reset Buttons -->
                        <div class="d-flex gap-3 mt-4">
                            <button type="button" id="uploadButton" class="btn btn-primary">
                                Upload Files
                            </button>
                            <button type="button" id="resetButton" class="btn btn-secondary">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/dropbox/dist/Dropbox-sdk.min.js"></script>
    <script src="{{ asset('assets/Dashboard/scripts/UploadFiles.js') }}"></script>
@endsection
