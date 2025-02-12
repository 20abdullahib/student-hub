@extends('website.layout.layout')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3>Resources: {{ $subject->name }}</h3>
                <p>{{ $subject->description ?? 'No description available.' }}</p>
            </div>
            <div class="card-body">
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
                <!-- Search Form in Subject Files -->
                <form id="search-form" class="mb-5 position-relative">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="resource-search" class="form-control"
                            placeholder="Search in {{ $subject->name }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                    <div id="" class="suggestions-container position-absolute"></div>
                </form>
                <!-- Display Folder Cards -->
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                    @foreach ($currentNode as $key => $node)
                        @if ($key !== '_files')
                            @php
                                // Build new folder path by appending the current folder with the folder name.
                                $newFolderPath = ($currentFolderPath ? $currentFolderPath . '/' : '') . $key;
                            @endphp
                            <div class="col">
                                <div class="card folder-card h-100 text-center p-4">
                                    <i class="bi bi-folder-fill display-4 text-primary"></i>
                                    <div class="card-body">
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
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                        @foreach ($currentNode['_files'] as $file)
                            @php
                                // Determine the appropriate icon based on file extension.
                                $extension = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
                                $iconClass = match ($extension) {
                                    'pdf' => 'bi bi-file-earmark-pdf-fill',
                                    'xlsx', 'xls' => 'bi bi-file-earmark-spreadsheet-fill',
                                    'doc', 'docx' => 'bi bi-file-earmark-word-fill',
                                    'zip', 'rar' => 'bi bi-file-earmark-zip-fill',
                                    'png', 'jpg', 'jpeg', 'gif' => 'bi bi-images',
                                    default => 'bi bi-file-earmark-text-fill',
                                };

                                // Format the file size.
                                $size = $file->size;
                                $sizeFormatted =
                                    $size >= 1024 * 1024
                                        ? round($size / (1024 * 1024), 2) . ' MB'
                                        : round($size / 1024, 2) . ' KB';
                            @endphp

                            <div class="col">
                                <div class="card file-card h-100 text-center p-4 border rounded shadow-sm">
                                    <i class="{{ $iconClass }} file-icon d-block mx-auto mb-3"
                                        style="font-size: 2rem;"></i>
                                    <div class="card-body">
                                        <h5 class="card-title text-truncate">{{ $file->name }}</h5>
                                        <p class="file-details small text-muted">
                                            Size: {{ $sizeFormatted }} | Type: {{ strtoupper($extension) }}
                                        </p>
                                        <div class="d-flex justify-content-center mt-3">
                                            <a href="{{ route('file.preview', $file->file_id) }}"
                                                class="btn btn-primary btn-sm me-2" target="_blank"
                                                aria-label="Preview {{ $file->name }}">
                                                Preview
                                            </a>
                                            <a href="{{ route('file.download', $file->file_id) }}"
                                                class="btn btn-success btn-sm" aria-label="Download {{ $file->name }}">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
