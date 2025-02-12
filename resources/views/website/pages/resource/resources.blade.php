@extends('website.layout.layout')

@section('title', 'Resources')

@section('custom-css')



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
                        Search Results for "<span id="search-query">{{ request()->query('query') }}</span>"
                    </h3>
                @endif

                <!-- Dropbox Files -->
                <div class="container mt-5">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                        @foreach ($subjects as $subject)
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
                                        <!-- Open Button -->
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
