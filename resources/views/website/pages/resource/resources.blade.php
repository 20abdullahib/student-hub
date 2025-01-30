@extends('website.layout.layout')

@section('title')
    resources
@endsection

@section('content')
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header d-lg-flex align-items-center justify-content-between position-relative">
            <form id="search-form" class="w-100" onsubmit="return false;">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" id="resource-search" class="form-control form-control-lg"
                        placeholder="Search by code or name of subject" aria-label="Search by code or name of subject"
                        aria-describedby="basic-addon2">
                    <button type="submit" class="btn btn-primary btn-lg" id="basic-addon2">Search</button>
                </div>
            </form>

            <!-- Suggestions Box -->
            <div id="resource-suggestions-container" class="suggestions-container position-absolute w-100"></div>
        </div>
        <!-- Card body -->
        <div class="card-body">
            <!-- Form -->
            <form class="row mb-4">
                <div class="col-xl-7 col-lg-6 col-md-4 col-12 mb-2 mb-lg-0">
                    <select id="department-filter" class="form-select">
                        <option value="">department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-12 mb-2 mb-lg-0">
                    <select id="branch-filter" class="form-select">
                        <option value="">branchs</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-12 mb-2 mb-lg-0">
                    <select id="sort-filter" class="form-select">
                        <option value="">Sort</option>
                        <option value="Newest">Newest</option>
                        <option value="Oldest">Oldest</option>
                    </select>
                </div>
            </form>
            <div>

                @if (request()->has('query') && request()->query('query') != '')
                    <h3 id="search-header">Search Results for "<span
                            id="search-query">{{ request()->query('query') }}</span>"</h3>
                @endif



                {{-- e --}}

                {{-- <div id="search-results">
                    @if ($subjects->isEmpty())
                        <p>No results found for "<span id="search-query-no-results">{{ $query }}</span>".</p>
                    @else
                        <div id="results-container" class="row">
                            @foreach ($subjects as $subject)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title">{{ $subject->title }}</h5>
                                        </div>
                                        <div class="card-body px-0">
                                            <div class="embed-responsive embed-responsive-16by9 mb-3 px-1">
                                                <i class="bi bi-folder-fill text-warning"
                                                    style="font-size: 6rem; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;"></i>
                                            </div>
                                            <div class="px-3">
                                                <div class="tags-container mb-3">
                                                    <a href="#" class="btn btn-outline-primary btn-sm me-1 mb-1"
                                                        style="pointer-events: none;">tags</a>
                                                </div>
                                                <button class="btn btn-primary position-relative see-details"
                                                    data-storage-path="{{ $subject->storage_path }}">
                                                    <i class="bi bi-info-circle"></i> See Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Container for Firebase file/folder output -->
                <div id="subject-details" style="display: none;">
                    <button id="back-button" class="btn btn-secondary mb-2">
                        <i class="bi bi-arrow-left"></i> Back
                    </button>
                    <div id="output" class="row"></div>
                </div> --}}

                {{-- e --}}


                {{-- <div id="search-results">
                    @if (empty($folders))
                        <p>No folders found.</p>
                    @else
                        <div id="results-container" class="row">
                            @foreach ($folders as $folder)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title">{{ $folder['name'] }}</h5>
                                        </div>
                                        <div class="card-body px-0">
                                            <div class="embed-responsive embed-responsive-16by9 mb-3 px-1">
                                                <i class="bi bi-folder-fill text-warning"
                                                    style="font-size: 6rem; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;"></i>
                                            </div>
                                            <div class="px-3">
                                                <div class="tags-container mb-3">
                                                    <a href="#" class="btn btn-outline-primary btn-sm me-1 mb-1"
                                                        style="pointer-events: none;">tags</a>
                                                </div>
                                                <button class="btn btn-primary position-relative see-details"
                                                    data-storage-path="{{ $folder['id'] }}">
                                                    <i class="bi bi-info-circle"></i> See Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div> --}}
                <style>
                    .custom-download-icon img {
                        width: 30px; /* Adjust size as needed */
                    }
                </style>
                

                <script src="https://cdn.fluidplayer.com/v3/current/fluidplayer.min.js"></script>

                <div class="container mt-5">
                    <h2>Dropbox Files</h2>

                    @foreach ($fileLinks as $file)
                        <div class="file-item mt-3">
                            <h5>{{ $file['name'] }}</h5>
                            @if ($file['is_pdf'])
                                <!-- Embed PDF -->
                                <embed src="{{ $file['link'] }}" type="application/pdf" width="100%" height="500px" />
                            @elseif ($file['is_video'])
                                <!-- Video Player with Fluid Player -->
                                <video id="video-{{ $loop->index }}" controls>
                                    <source src="{{ $file['link'] }}" type="video/mp4">
                                    <!-- Add more sources here if needed -->
                                </video>
                                <script>
                                    fluidPlayer("video-{{ $loop->index }}", {
                                        "layoutControls": {
                                            "controlBar": {
                                                "autoHideTimeout": 3,
                                                "animated": true,
                                                "autoHide": true
                                            },
                                            "autoPlay": false,
                                            "mute": false,
                                            "allowTheatre": true,
                                            "playbackRateEnabled": true,
                                            "allowDownload": true,
                                            "playButtonShowing": true,
                                            "fillToContainer": false,
                                            "posterImage": ""
                                        }
                                    });
                                </script>
                            @else
                                <!-- Display direct download link for other files -->
                                <a href="{{ $file['link'] }}" target="_blank" class="btn btn-primary">Download</a>
                            @endif
                        </div>
                    @endforeach
                </div>
                <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            </div>
        @endsection
