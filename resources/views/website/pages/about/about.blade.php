@extends('website.layout.layout')

@section('title')
    About Teem
@endsection
@section('content')
    <section class="hero-section py-md-8 py-6 snipcss-pGQdz style-4pKBU d-block overflow-x-hidden" id="style-4pKBU">
        {{-- @php
            $generations = [
                '2021' => [
                    ['name' => 'Andrew Lupien', 'role' => 'Quality Assurance Engineer', 'image' => 'mentor-img-2.jpg'],
                    ['name' => 'Bernice Perry', 'role' => 'Senior Business Analyst', 'image' => 'mentor-img-3.jpg'],
                    ['name' => 'Patrice Long', 'role' => 'Senior Data Engineer', 'image' => 'mentor-img-4.jpg'],
                    ['name' => 'Akshay Sharma', 'role' => 'Frontend Engineer', 'image' => 'mentor-img-5.jpg'],
                    ['name' => 'Jessica Lupien', 'role' => 'UI/UX Designer', 'image' => 'mentor-img-6.jpg'],
                    ['name' => 'Cathy Diehl', 'role' => 'Quality Assurance Engineer', 'image' => 'mentor-img-7.jpg'],
                    ['name' => 'Patrice Long', 'role' => 'Software Engineer', 'image' => 'mentor-img-8.jpg'],
                    ['name' => 'Akshay Sharma', 'role' => 'Frontend Engineer', 'image' => 'mentor-img-1.jpg'],
                    ['name' => 'Jessica Lupien', 'role' => 'Quality Assurance Engineer', 'image' => 'mentor-img-2.jpg'],
                    ['name' => 'Bernice Perry', 'role' => 'Senior Business Analyst', 'image' => 'mentor-img-3.jpg'],
                    ['name' => 'Patrice Long', 'role' => 'Senior Data Engineer', 'image' => 'mentor-img-4.jpg'],
                    ['name' => 'Cathy Diehl', 'role' => 'Frontend Engineer', 'image' => 'mentor-img-5.jpg'],
                    ['name' => 'Jessica Lupien', 'role' => 'UX/UI Designer', 'image' => 'mentor-img-6.jpg'],
                ],
                // Add more generations here if needed
            ];
        @endphp --}}

        {{-- @foreach ($years as $year)
            <div class="d-flex px-5">
                <div class="d-flex align-items-center p-3">
                    <a href="#">{{ 'See this generation ' . $year }}</a>
                </div>
                <div>
                    <div class="position-relative d-flex overflow-x-hidden py-lg-4 pt-4">
                        <div class="animate-marquee d-flex gap-3">
                            @foreach ($generations->where('year_joined', $year) as $student)
                                <a href="#!"
                                    class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                                    style="width: 200px !important">
                                    <div class="p-3">
                                        <img src="{{ $student->image }}" alt="{{ $student->name }}"
                                            class="avatar avatar-xl rounded-circle">
                                        <div class="mt-3">
                                            <h3 class="mb-0 h4">{{ $student->name }}</h3>
                                            <span class="text-gray-800">{{ $student->branch_name }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach --}}

        @foreach ($years as $year)
            <div class="d-flex px-5">
                <div class="d-flex align-items-center p-3">
                    <a href="{{ route('about.showGeneration', $year) }}">{{ 'See this generation ' . $year }}</a>
                </div>
                <div class="position-relative d-flex overflow-x-hidden py-lg-4 pt-4">
                    <div class="animate-marquee d-flex gap-3">
                        @for ($i = 0; $i < 2; $i++)
                            @foreach ($generations->where('year_joined', $year)->take(10) as $student)
                                <a 
                                    class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                                    style="width: 200px !important">
                                    <div class="p-3">
                                        <img src="{{ $student->image }}" alt="{{ $student->name }}"
                                            class="avatar avatar-xl rounded-circle">
                                        <div class="mt-3">
                                            <h3 class="mb-0 h4">{{ $student->name }}</h3>
                                            <span class="text-gray-800">{{ $student->branch->name }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endfor
                    </div>
                </div>
            </div>
        @endforeach

        {{-- work --}}
        {{-- @foreach ($years as $year)
        <div class="d-flex px-5">
            <div class="d-flex align-items-center p-3">
                <a href="#">{{ 'See this generation ' . $year }}</a>
            </div>
            <div class="position-relative d-flex overflow-x-hidden py-lg-4 pt-4">
                <div class="animate-marquee d-flex gap-3">
                    @foreach ($generations->where('year_joined', $year) as $student)
                        <a href="#!"
                            class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                            style="width: 200px !important">
                            <div class="p-3">
                                <img src="{{ $student->image }}" alt="{{ $student->name }}"
                                    class="avatar avatar-xl rounded-circle">
                                <div class="mt-3">
                                    <h3 class="mb-0 h4">{{ $student->name }}</h3>
                                    <span class="text-gray-800">{{ $student->branch->name }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach --}}
        {{-- work --}}
        {{-- @foreach ($years as $year)
            <div class="d-flex align-items-center p-3">
                <a href="#">{{ 'See this generation ' . $year->year }}</a>
            </div>
            <div>
                <div class="position-relative d-flex overflow-x-hidden py-lg-4 pt-4">
                    <div class="animate-marquee d-flex gap-3">
                        @foreach ($people as $person)
                            <a href="#!"
                                class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                                style="width: 200px !important">
                                <div class="p-3">
                                    <img src="https://codescandy.com/geeks-bootstrap-5/assets/images/mentor/{{ $person['image'] }}"
                                        alt="{{ $person['name'] }}" class="avatar avatar-xl rounded-circle">
                                    <div class="mt-3">
                                        <h3 class="mb-0 h4">{{ $person['name'] }}</h3>
                                        <span class="text-gray-800">{{ $person['role'] }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach --}}



    </section>
@endsection
