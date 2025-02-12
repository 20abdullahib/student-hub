{{-- @extends('website.layout.layout')

@section('content')
<div class="container mt-5">
    <!-- Subject Title & Description -->
    <h1>{{ $subject->name }}</h1>
    <p>{{ $subject->description ?? 'No description available.' }}</p>

    @php
        /**
         * Build a nested tree from a flat list of files.
         *
         * For each file:
         * - Remove any leading/trailing slashes.
         * - If the trimmed path is empty, place the file in the root.
         * - Otherwise, split the path by "/" and consider the last segment the file name.
         *   The remaining segments form the folder hierarchy.
         *
         * The resulting structure will be an associative array where:
         *   - Root-level files are stored under $tree['_files'].
         *   - Files in folders are stored under keys representing folder names.
         */
        function buildTree($files)
        {
            $tree = [];
            foreach ($files as $file) {
                // Remove leading/trailing slashes and whitespace.
                $trimmedPath = trim($file->path, "/ \t\n\r\0\x0B");
                
                // If path is empty, file is at the root.
                if ($trimmedPath === '') {
                    if (!isset($tree['_files'])) {
                        $tree['_files'] = [];
                    }
                    $tree['_files'][] = $file;
                } else {
                    $parts = explode('/', $trimmedPath);
                    // If more than one part, assume the last segment is the file name.
                    $folderParts = count($parts) > 1 ? array_slice($parts, 0, -1) : [];
                    
                    $current = &$tree;
                    foreach ($folderParts as $folder) {
                        if (!isset($current[$folder])) {
                            $current[$folder] = [];
                        }
                        $current = &$current[$folder];
                    }
                    
                    if (!isset($current['_files'])) {
                        $current['_files'] = [];
                    }
                    $current['_files'][] = $file;
                }
            }
            return $tree;
        }

        // Build the complete folder tree from the subject's files.
        $tree = buildTree($subject->files);

        // Get the current folder from the query string (e.g. ?folder=SomeFolder/Subfolder)
        $currentFolderPath = request()->get('folder', '');
        $currentNode = $tree;
        if ($currentFolderPath !== '') {
            $parts = explode('/', $currentFolderPath);
            foreach ($parts as $part) {
                if (isset($currentNode[$part])) {
                    $currentNode = $currentNode[$part];
                } else {
                    // Folder not found – reset to an empty array.
                    $currentNode = [];
                    break;
                }
            }
        }
    @endphp

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('resources.subjects.show', $subject->id) }}">Home</a>
            </li>
            @if ($currentFolderPath)
                @php
                    $breadcrumbParts = explode('/', $currentFolderPath);
                    $accumulated = '';
                @endphp
                @foreach ($breadcrumbParts as $part)
                    @php $accumulated .= ($accumulated ? '/' : '') . $part; @endphp
                    <li class="breadcrumb-item">
                        <a href="{{ route('resources.subjects.show', $subject->id) }}?folder={{ urlencode($accumulated) }}">
                            {{ $part }}
                        </a>
                    </li>
                @endforeach
            @endif
        </ol>
    </nav>

    <!-- "Back" Button (only if not at the root) -->
    @if ($currentFolderPath)
        @php
            $parts = explode('/', $currentFolderPath);
            array_pop($parts);
            $parentPath = implode('/', $parts);
        @endphp
        <a href="{{ route('resources.subjects.show', $subject->id) }}{{ $parentPath ? '?folder=' . urlencode($parentPath) : '' }}" class="btn btn-secondary mb-3">
            Back
        </a>
    @endif

    <!-- Display Folder Cards at the Current Level -->
    <div class="row">
        @foreach ($currentNode as $key => $node)
            @if ($key !== '_files')
                @php
                    // Build the new folder path by appending the folder name to the current folder path.
                    $newFolderPath = trim(($currentFolderPath ? $currentFolderPath . '/' : '') . $key, '/');
                @endphp
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-folder-fill text-primary me-3" style="font-size: 1.5rem;"></i>
                            <a href="{{ route('resources.subjects.show', $subject->id) }}?folder={{ urlencode($newFolderPath) }}"
                               class="stretched-link text-decoration-none">
                                {{ $key }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Display Files at the Current Level -->
    @if (isset($currentNode['_files']) && count($currentNode['_files']) > 0)
        <div class="list-group mt-4">
            @foreach ($currentNode['_files'] as $file)
                @php
                    // Determine an icon based on the file extension.
                    $extension = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
                    $iconClass = 'bi bi-file-earmark-text'; // default icon
                    if ($extension === 'pdf') {
                        $iconClass = 'bi bi-file-earmark-pdf';
                    } elseif (in_array($extension, ['xlsx', 'xls'])) {
                        $iconClass = 'bi bi-file-earmark-spreadsheet';
                    } elseif (in_array($extension, ['doc', 'docx'])) {
                        $iconClass = 'bi bi-file-earmark-word';
                    } elseif (in_array($extension, ['zip', 'rar'])) {
                        $iconClass = 'bi bi-file-earmark-zip';
                    } elseif (in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
                        $iconClass = 'bi bi-images';
                    }
                    // Format file size (assumes size is in bytes).
                    $size = $file->size;
                    $sizeFormatted = ($size >= 1024 * 1024)
                        ? round($size / (1024 * 1024), 2) . ' MB'
                        : round($size / 1024, 2) . ' KB';
                @endphp

                <a href="{{ $file->link ?? '#' }}" class="list-group-item list-group-item-action file-card d-flex align-items-center">
                    <i class="{{ $iconClass }} file-icon me-3" style="font-size: 1.5rem;"></i>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">{{ $file->name }}</h5>
                        <p class="mb-0 text-muted">Size: {{ $sizeFormatted }} | Type: {{ strtoupper($extension) }}</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary ms-3">Download</button>
                </a>
            @endforeach
        </div>
    @else
        <p>No files in this folder.</p>
    @endif
</div>
@endsection --}}

{{-- ========================================================================== --}}

@extends('website.layout.layout')

@section('content')
    <div class="container mt-5">
        <h1>{{ $subject->name }}</h1>
        <p>{{ $subject->description ?? 'No description available.' }}</p>

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                @foreach ($breadcrumbs as $crumb)
                    <li class="breadcrumb-item">
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                    </li>
                @endforeach
            </ol>
        </nav>

        <!-- Display Folder Cards -->
        <div class="row">
            @foreach ($currentNode as $key => $node)
                @if ($key !== '_files')
                    @php
                        // Build the new folder path (append current folder path with the folder name)
                        $newFolderPath = ($currentFolderPath ? $currentFolderPath . '/' : '') . $key;
                    @endphp
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body d-flex align-items-center">
                                <i class="bi bi-folder-fill text-primary me-3" style="font-size:1.5rem;"></i>
                                <a href="{{ route('resources.subjects.show', $subject->id) }}?folder={{ urlencode($newFolderPath) }}"
                                    class="stretched-link text-decoration-none">
                                    {{ $key }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Display Files -->
        @if (isset($currentNode['_files']) && count($currentNode['_files']) > 0)
            <div class="list-group mt-4">
                @foreach ($currentNode['_files'] as $file)
                    @php
                        // Choose an icon based on the file extension.
                        $extension = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
                        $iconClass = 'bi bi-file-earmark-text';
                        if ($extension === 'pdf') {
                            $iconClass = 'bi bi-file-earmark-pdf';
                        } elseif (in_array($extension, ['xlsx', 'xls'])) {
                            $iconClass = 'bi bi-file-earmark-spreadsheet';
                        } elseif (in_array($extension, ['doc', 'docx'])) {
                            $iconClass = 'bi bi-file-earmark-word';
                        } elseif (in_array($extension, ['zip', 'rar'])) {
                            $iconClass = 'bi bi-file-earmark-zip';
                        } elseif (in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
                            $iconClass = 'bi bi-images';
                        }

                        // Format the file size.
                        $size = $file->size;
                        $sizeFormatted =
                            $size >= 1024 * 1024
                                ? round($size / (1024 * 1024), 2) . ' MB'
                                : round($size / 1024, 2) . ' KB';
                    @endphp

                    <a href="{{ $file->link ?? '#' }}"
                        class="list-group-item list-group-item-action file-card d-flex align-items-center">
                        <i class="{{ $iconClass }} file-icon me-3" style="font-size:1.5rem;"></i>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $file->name }}</h5>
                            <p class="mb-0 text-muted">Size: {{ $sizeFormatted }} | Type: {{ strtoupper($extension) }}</p>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary ms-3">Download</button>
                    </a>
                @endforeach
            </div>
        @else
            <p>No files in this folder.</p>
        @endif
    </div>
@endsection
