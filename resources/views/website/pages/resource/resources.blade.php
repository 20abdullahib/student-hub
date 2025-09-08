@extends('website.layout.layout')

@section('title', 'Resources')

@section('content')
    <div class="container my-4">
        <div class="card">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3>Resources</h3>
            </div>
            <div class="card-body">
                @include('website.pages.resource.includes.header-search')
                <!-- Optional: Display search query results header -->
                @if (request()->filled('query'))
                    <h3 id="search-header">
                        {{-- Search Results for "<span id="search-query">{{ request()->query('query') }}</span>" --}}
                    </h3>
                @endif

                <div id="results-container"
                    class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4"></div>
                <!-- Dropbox Files -->
                <div class="container mt-5" id="static-resources">
                    @if($subjects->count() > 0)
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                            @foreach ($subjects as $subject)
                                @if($subject->files->count() > 0)
                                    <div class="col">
                                        <div class="card folder-card h-100 text-center p-4">
                                            <!-- Folder Icon -->
                                            <i class="bi bi-folder-fill display-4 text-primary"></i>
                                            <!-- Card Body -->
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $subject->name }}</h5>
                                                <p class="card-text">
                                                    {{ $subject->description ?? 'No description available.' }}
                                                </p>
                                                <p class="card-text small text-muted">
                                                    {{ $subject->files_count ?? $subject->files->count() }} file(s) available
                                                </p>
                                                <!-- Open Button -->
                                                <a href="{{ route('resources.subjects.show', $subject->id) }}"
                                                    class="btn btn-primary">Open</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $subjects->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-folder-x display-1 text-muted"></i>
                            <h4 class="mt-3">No Resources Found</h4>
                            <p class="text-muted">No subjects with files are available at the moment.</p>
                        </div>
                    @endif
                </div>
                <div id="pageinit-container"></div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    {{-- <!-- Fluid Player -->
    <script src="https://cdn.fluidplayer.com/v3/current/fluidplayer.min.js"></script>
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
@endsection
