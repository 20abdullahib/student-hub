@extends('website.layout.layout')


@section('content')
    <section class="hero-section py-md-8 py-6 snipcss-pGQdz style-4pKBU" id="style-4pKBU">
        <div class="container py-lg-6">
            <div
                class="row align-items-center gy-4 justify-content-center snipcss0-0-0-1 tether-element-attached-top tether-element-attached-center tether-target-attached-top tether-target-attached-center">
                <div class="col-xxl-5 col-xl-6 col-md-10 snipcss0-1-1-2">
                    <div class="d-flex flex-column gap-5 text-center snipcss0-2-2-3">
                        <div class="d-flex flex-column gap-2 snipcss0-3-3-4">
                            <h1 class="mb-0 display-2 fw-bold snipcss0-4-4-6">
                                <span class="snipcss0-5-6-7">Magic Teem</span>
                                <span class="text-primary snipcss0-5-6-8" id="typed-strings"></span><span
                                    class="typed-cursor snipcss0-5-6-9" aria-hidden="true"></span>
                            </h1>
                        </div>
                        <div class="d-flex flex-column gap-3 snipcss0-3-3-11">
                            {{-- <form class="snipcss0-4-11-12">
                                <div class="input-group mb-3 snipcss0-5-12-13">
                                    <input type="text" class="form-control form-control-lg snipcss0-6-13-14"
                                        placeholder="Search by code or name of subject" aria-label="Search by code or name of subject"
                                        aria-describedby="basic-addon2">
                                    <button class="btn btn-primary btn-lg snipcss0-6-13-15" id="basic-addon2">Search</button>
                                </div>
                            </form> --}}


                            <form id="search-form" class="snipcss0-4-11-12" onsubmit="return false;">
                                @csrf
                                <div class="input-group mb-3 snipcss0-5-12-13">
                                    <input type="text" id="home-search"
                                        class="form-control form-control-lg snipcss0-6-13-14"
                                        placeholder="Search by code or name of subject"
                                        aria-label="Search by code or name of subject" aria-describedby="basic-addon2">
                                    <button type="submit" class="btn btn-primary btn-lg snipcss0-6-13-15"
                                        id="basic-addon2">Search</button>
                                </div>
                            </form>

                            <!-- Suggestions Box -->
                            <div id="home-suggestions-container" class="suggestions-container"></div>
                            <div class="gap-2 d-flex flex-wrap justify-content-center snipcss0-4-11-16">
                                {{-- <a href="{{route('resources.filter','Mathematics')}}" class="btn btn-tag btn-sm snipcss0-5-16-17">Mathematics</a>
                                <a href="{{route('resources.filter','Physics')}}" class="btn btn-tag btn-sm snipcss0-5-16-18">Physics</a>
                                <a href="{{route('resources.filter','Chemistry')}}" class="btn btn-tag btn-sm snipcss0-5-16-19">Chemistry</a>
                                <a href="{{route('resources.filter','Geology')}}" class="btn btn-tag btn-sm snipcss0-5-16-20">Geology</a>
                                <a href="{{route('resources.filter','Botany')}}" class="btn btn-tag btn-sm snipcss0-5-16-21">Botany</a>
                                <a href="{{route('resources.filter','Animals')}}" class="btn btn-tag btn-sm snipcss0-5-16-22">Animals</a> --}}

                                @foreach ($departments as $department)
                                    <a href="{{ route('resources.filter.departmentbranch', $department->id) }}"
                                        class="btn btn-tag btn-sm snipcss0-5-16-17">{{ $department->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            {{--
<div class="position-relative d-flex overflow-x-hidden py-lg-4 pt-4">
                <div class=".animate-marquee-2 d-flex gap-3">
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-2.jpg"
                                alt="mentor 1" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Andrew Lupien</h3>
                                <span class="text-gray-800">HR</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-3.jpg"
                                alt="mentor 2" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Bernice Perry</h3>
                                <span class="text-gray-800">HR</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-4.jpg"
                                alt="mentor 3" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Patrice Long</h3>
                                <span class="text-gray-800">BR</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-5.jpg"
                                alt="mentor 4" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Akshay Sharma</h3>
                                <span class="text-gray-800">OC</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-6.jpg"
                                alt="mentor 5" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Jessica Lupien</h3>
                                <span class="text-gray-800">Team leader</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-7.jpg"
                                alt="mentor 6" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Cathy Diehl</h3>
                                <span class="text-gray-800">HR</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-8.jpg"
                                alt="mentor 7" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Patrice Long</h3>
                                <span class="text-gray-800">Assestant Leader</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-1.jpg"
                                alt="mentor 8" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Akshay Sharma</h3>
                                <span class="text-gray-800">BR</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-2.jpg"
                                alt="mentor 9" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Jessica Lupien</h3>
                                <span class="text-gray-800">HR</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-3.jpg"
                                alt="mentor 10" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Bernice Perry</h3>
                                <span class="text-gray-800">HR</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-4.jpg"
                                alt="mentor 11" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Patrice Long</h3>
                                <span class="text-gray-800">Assestant Leader</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-5.jpg"
                                alt="mentor 12" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Cathy Diehl</h3>
                                <span class="text-gray-800">Frontend Engineer</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-6.jpg"
                                alt="mentor 13" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Jessica Lupien</h3>
                                <span class="text-gray-800">OC</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-7.jpg"
                                alt="mentor 14" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Andrew Lupien</h3>
                                <span class="text-gray-800">HR</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-8.jpg"
                                alt="mentor 15" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Cathy Diehl</h3>
                                <span class="text-gray-800">IT</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-1.jpg"
                                alt="mentor 16" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">James Anderson</h3>
                                <span class="text-gray-800">OC</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-2.jpg"
                                alt="mentor 18" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Cathy Diehl</h3>
                                <span class="text-gray-800">HR</span>
                            </div>
                        </div>
                    </a>
                    <a href="#!"
                        class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 200px !important">
                        <div class="p-3">
                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/mentor/mentor-img-3.jpg"
                                alt="mentor 19" class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">Akshay Sharma</h3>
                                <span class="text-gray-800">BR</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
--}}










        </div>
    </section>
    {{-- <section class="middel-section">
        <div class="tab-pane tab-example-design fade bg-light p-4 rounded-3 active show snipcss-tCfa9"
            id="pills-card-2col-design" role="tabpanel" aria-labelledby="pills-card-2col-design-tab">
            <div class="py-8 bg-light">
                <div class="container">
                    <div class="row mb-8 justify-content-center">
                        <div class="col-lg-8 col-md-12 col-12 text-center">
                            <h2 class="mb-2 display-4 fw-bold">What will you learn?</h2>
                            <p class="lead">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad error possimus
                                dolorem minus aliquid ipsum suscipit magnam distinctio non aliquam?</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="card mb-4">
                                <div class="card-body p-6">
                                    <div class="d-md-flex mb-4">
                                        <div class="mb-3 mb-md-0">
                                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/svg/feature-icon-1.svg"
                                                alt="icon" class="bg-primary icon-shape icon-xxl rounded-circle">
                                        </div>
                                        <div class="ms-md-4 mt-3">
                                            <h2 class="fw-bold mb-1"> Introduction To Mathematics
                                            </h2>
                                        </div>
                                    </div>
                                    <p class="mb-4 fs-4"> In et tempus dui, in porta dolor. Donec molestie a purus ut
                                        interdum. Donec quis felis dignissim, luctus libero ornare. </p>
                                    <a href="#" class="btn-link" data-bs-toggle="modal"
                                        data-bs-target="#MathematicsModal">View Details<i class="bi bi-plus fa-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="card mb-4">
                                <div class="card-body p-6">
                                    <div class="d-md-flex mb-4">
                                        <div class="mb-3 mb-md-0">
                                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/svg/feature-icon-2.svg"
                                                alt="icon" class="bg-primary icon-shape icon-xxl rounded-circle">
                                        </div>
                                        <div class="ms-md-4">
                                            <h2 class="fw-bold mb-1">JavaScript Beginning</h2>
                                            <p class="text-uppercase fs-6 fw-semibold mb-0">
                                                <span class="text-dark">Courses - 2</span>
                                                <span class="ms-3">4 Lessons</span>
                                                <span class="ms-3">32 Min</span>
                                            </p>
                                        </div>
                                    </div>
                                    <p class="mb-4 fs-4"> Malesuada fames ac turpis egesta mpor tempus tincidunt. Aliquam
                                        congue lacus ac tellus consectetur malesuada. </p>
                                    <a href="#" class="btn-link" data-bs-toggle="modal"
                                        data-bs-target="#courseModal">View Details<i class="bi bi-plus fa-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="card mb-4">
                                <div class="card-body p-6">
                                    <div class="d-md-flex mb-4">
                                        <div class="mb-3 mb-md-0">
                                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/svg/feature-icon-3.svg"
                                                alt="icon" class="bg-primary icon-shape icon-xxl rounded-circle">
                                        </div>
                                        <div class="ms-md-4">
                                            <h2 class="fw-bold mb-1">Variables and Constants</h2>
                                            <p class="text-uppercase fs-6 fw-semibold mb-0">
                                                <span class="text-dark">Courses - 3</span>
                                                <span class="ms-3">8 Lessons</span>
                                                <span class="ms-3">10 Min</span>
                                            </p>
                                        </div>
                                    </div>
                                    <p class="mb-4 fs-4"> Aliquam pulvinar eros a dictur vitae diam imperdiet, ornare
                                        turpis vequet elit nec, imperdiet lectuna liquam qs. </p>
                                    <a href="#" class="btn-link" data-bs-toggle="modal"
                                        data-bs-target="#courseModal">View Detalies<i class="bi bi-plus fa-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="card mb-4">
                                <div class="card-body p-6">
                                    <div class="d-md-flex mb-4">
                                        <div class="mb-3 mb-md-0">
                                            <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/svg/feature-icon-4.svg"
                                                alt="icon" class="bg-primary icon-shape icon-xxl rounded-circle">
                                        </div>
                                        <div class="ms-md-4">
                                            <h2 class="fw-bold mb-1">Types and Operators</h2>
                                            <p class="text-uppercase fs-6 fw-semibold mb-0">
                                                <span class="text-dark">Courses - 4</span>
                                                <span class="ms-3">10 Lessons</span>
                                                <span class="ms-3">32 Min</span>
                                            </p>
                                        </div>
                                    </div>
                                    <p class="mb-4 fs-4"> In lobortis quam eu augue spendisse imperdiet nec orci ipsum,
                                        tempus pharetra posuere imperdiet, lacinia a nisl. </p>
                                    <a href="#" class="btn-link" data-bs-toggle="modal"
                                        data-bs-target="#courseModal">View Chapter Details<i class="bi bi-plus fa-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-custom">
            <div class="modal fade" id="MathematicsModal" tabindex="-1" aria-labelledby="MathematicsModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header py-4 align-items-lg-center">
                            <div class="d-lg-flex">
                                <div class="mb-3 mb-lg-0">
                                    <img src="https://codescandy.com/geeks-bootstrap-5/assets/Website/images/svg/feature-icon-1.svg"
                                        alt="" class="bg-primary icon-shape icon-xxl rounded-circle">
                                </div>
                                <div class="ms-lg-4">
                                    <h2 class="fw-bold mb-md-1 mb-3">Introduction to JavaScript
                                    </h2>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item ps-0">
                                    <button onclick="openModal()"
                                        class="d-flex justify-content-between align-items-center text-inherit"
                                        style="border:none; background:none; padding:0; cursor:pointer;">
                                        <div class="text-truncate">
                                            <span class="icon-shape bg-light text-primary icon-sm rounded-circle ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-play-fill text-primary"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <span>Introduction To Mathematics</span>
                                        </div>
                                        <div class="text-truncate">
                                            <span>1m 7s</span>
                                        </div>
                                    </button>

                                    <!-- Nested Modal  -->
                                    <div id="myModal"
                                        style="display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
                                        <div
                                            style="background-color:white; margin:5% auto; padding:20px; border:1px solid #888; width:80%; max-width:700px;">
                                            <div class="modal-header pt-2 align-items-lg-center">
                                                <h2>Introduction To Mathematics</h2>
                                                <button onclick="closeModal()" class="btn-close"></button>
                                            </div>
                                            <div class="modal-body d-flex justify-content-center">
                                                <iframe width="100%" height="315"
                                                    src="https://www.youtube-nocookie.com/embed/C0DPdy98e4c?si=4sJ0LT3uLZOLU7QG"
                                                    title="YouTube video player" frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                    referrerpolicy="strict-origin-when-cross-origin"
                                                    allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
@endsection
