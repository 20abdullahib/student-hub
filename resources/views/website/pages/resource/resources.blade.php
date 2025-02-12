@extends('website.layout.layout')

@section('title', 'Resources')

@section('custom-css')

<style>
    /* Custom Styles */
    .file-card {
        border: 2px solid #0d6efd;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .file-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    }

    .file-icon {
        font-size: 4rem;
        color: #0d6efd;
    }

    .file-details {
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
    <div class="container my-4">
        <div class="card">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3>Resources</h3>
            </div>
            <div class="card-body">
                <form id="search-form" class="mb-3 position-relative">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="resource-search" class="form-control"
                            placeholder="Search by code or name of subject">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                    {{-- <div id="resource-suggestions-container" class="position-absolute w-75"></div> --}}
                    <div id="resource-suggestions-container" class="suggestions-container position-absolute"></div>
                </form>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <select id="department-filter" class="form-select">
                            <option value="">Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="branch-filter" class="form-select">
                            <option value="">Branches</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="sort-filter" class="form-select">
                            <option value="">Sort</option>
                            <option value="Newest">Newest</option>
                            <option value="Oldest">Oldest</option>
                        </select>
                    </div>
                </div>

                <!-- Card Body: Filters and Dropbox Files -->


                <!-- Optional: Display search query results header -->
                @if (request()->filled('query'))
                    <h3 id="search-header">
                        Search Results for "<span id="search-query">{{ request()->query('query') }}</span>"
                    </h3>
                @endif

                <!-- Dropbox Files -->
                <!-- Dropbox Files -->
                <!-- Dropbox Files -->
                <!-- Dropbox Files -->
                <div class="container mt-5">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                        @foreach ($subjects as $subject)
                            <div class="col">
                                <div class="card h-100 text-center p-4">
                                    <!-- Folder Icon -->
                                    <i class="bi bi-folder-fill display-4 text-primary"></i>

                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $subject->name }}</h5>
                                        <p class="card-text">
                                            {{ $subject->description ?? 'No description available.' }}
                                        </p>
                                        <!-- Replace the route name and parameter as needed -->
                                        <a href="{{ route('resources.subjects.show', $subject->id) }}"
                                            class="btn btn-primary">Open</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $subjects->links() }}
                    </div>
                </div>


                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    <!-- File Card 1 -->
                    <div class="col">
                        <div class="card file-card h-100 text-center p-4">
                            <i class="bi bi-file-earmark-pdf-fill file-icon"></i>
                            <div class="card-body">
                                <h5 class="card-title">Report.pdf</h5>
                                <p class="file-details">Size: 2 MB | Type: PDF</p>
                                <a href="#" class="btn btn-primary btn-sm me-2">Preview</a>
                                <a href="#" class="btn btn-success btn-sm">Download</a>
                            </div>
                        </div>
                    </div>

                    <!-- File Card 2 -->
                    <div class="col">
                        <div class="card file-card h-100 text-center p-4">
                            <i class="bi bi-file-earmark-spreadsheet-fill file-icon"></i>
                            <div class="card-body">
                                <h5 class="card-title">Data.xlsx</h5>
                                <p class="file-details">Size: 500 KB | Type: Excel</p>
                                <a href="#" class="btn btn-primary btn-sm me-2">Preview</a>
                                <a href="#" class="btn btn-success btn-sm">Download</a>
                            </div>
                        </div>
                    </div>

                    <!-- File Card 3 -->
                    <div class="col">
                        <div class="card file-card h-100 text-center p-4">
                            <i class="bi bi-file-earmark-word-fill file-icon"></i>
                            <div class="card-body">
                                <h5 class="card-title">Document.docx</h5>
                                <p class="file-details">Size: 1.5 MB | Type: Word</p>
                                <a href="#" class="btn btn-primary btn-sm me-2">Preview</a>
                                <a href="#" class="btn btn-success btn-sm">Download</a>
                            </div>
                        </div>
                    </div>

                    <!-- File Card 4 -->
                    <div class="col">
                        <div class="card file-card h-100 text-center p-4">
                            <i class="bi bi-images file-icon"></i>
                            <div class="card-body">
                                <h5 class="card-title">Images.zip</h5>
                                <p class="file-details">Size: 10 MB | Type: ZIP</p>
                                <a href="#" class="btn btn-primary btn-sm me-2">Preview</a>
                                <a href="#" class="btn btn-success btn-sm">Download</a>
                            </div>
                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Fluid Player -->
    <script src="https://cdn.fluidplayer.com/v3/current/fluidplayer.min.js"></script>
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection



{{-- @include('website.pages.resource.partials.nested-folders', [ --}}
