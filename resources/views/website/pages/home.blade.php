@extends('website.layout.layout')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section py-md-8 py-6 snipcss-pGQdz style-4pKBU" id="style-4pKBU">
        <div class="container py-lg-6">
            <div class="row align-items-center gy-4 justify-content-center">
                <div class="col-xxl-5 col-xl-6 col-md-10">
                    <div class="d-flex flex-column gap-5 text-center">
                        <!-- Hero Title -->
                        <div class="d-flex flex-column gap-2">
                            <h1 class="mb-0 display-2 fw-bold">
                                <span>Magic Teem</span>
                                <span class="text-primary" id="typed-strings"></span>
                                <span class="typed-cursor" aria-hidden="true"></span>
                            </h1>
                        </div>
                        <!-- Search Form -->
                        <div class="d-flex flex-column gap-3">
                            <form id="search-form" onsubmit="return false;">
                                @csrf
                                <div class="input-group mb-3 position-relative">
                                    <input type="text" id="home-search" class="form-control form-control-lg"
                                        placeholder="Search by code or name of subject"
                                        aria-label="Search by code or name of subject" aria-describedby="basic-addon2"
                                        oninput="toggleClearIcon()">
                                    <!-- Clear Icon Inside Input -->
                                    <span class="position-absolute top-50 translate-middle-y end-0 me-3 d-none"
                                        style="z-index: 5; cursor: pointer;" id="clear-home-search" onclick="clearSearch()">
                                        <i class="fa fa-times text-muted"></i>
                                    </span>
                                    <button type="submit" class="btn btn-primary btn-lg" id="basic-addon2">Search</button>
                                </div>
                            </form>

                            <!-- Suggestions Box -->
                            <div id="home-suggestions-container" class="suggestions-container" style="z-index: 1;"></div>

                            <!-- Department Tags -->
                            <div class="gap-2 d-flex flex-wrap justify-content-center">
                                @foreach ($departments as $department)
                                    <a href="{{ route('resources.filter', ['department' => $department->id]) }}"
                                        class="btn btn-tag btn-sm">{{ $department->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mentor Cards -->
        <div>
            <div class="position-relative d-flex overflow-x-hidden py-lg-4 pt-4">
                <div class=".animate-marquee-2 d-flex gap-3">
                    @for ($i = 0; $i < 2; $i++)
                        @foreach ($data as $mentor)
                            <a href="#!"
                                class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                                style="width: 200px !important">
                                <div class="p-3">
                                    <img src="{{ $mentor->image }}" alt="{{ $mentor->name }}"
                                        class="avatar avatar-xl rounded-circle">
                                    <div class="mt-3">
                                        <h3 class="mb-0 h4">{{ $mentor->name }}</h3>
                                        <span class="text-gray-800">{{ $mentor->role }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endfor
                </div>
            </div>
        </div>
    </section>
@endsection
