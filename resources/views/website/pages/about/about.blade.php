@extends('website.layout.layout')

@section('title')
    About Teem
@endsection
@section('custom-css')
    <link rel="stylesheet" href="{{ asset('assets\Website\css\frame\frame.css') }}">
@endsection
@section('content')
    <section class="hero-section py-md-8 py-6 mt-3 snipcss-pGQdz style-4pKBU d-block overflow-x-hidden" id="style-4pKBU">
        @foreach ($years as $year)
            <div class="d-flex px-lg-3 px-md-0 mt-3">
                <div class="d-flex align-items-center p-lg-3 ps-1 pe-2">
                    <a href="{{ route('about.showGeneration', $year) }}">{{ 'See this generation ' . $year }}</a>
                </div>
                <div class="patch">Patch {{ $generations->where('year_joined', $year)->pluck('patch')->first() }}</div>
                <div class="custom-frame position-relative d-flex overflow-x-hidden py-lg-4 pt-4">
                    <div class="animate-marquee d-flex gap-3">
                        @for ($i = 0; $i < 2; $i++)
                            @foreach ($generations->where('year_joined', $year)->take(10) as $student)
                                <a class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
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
    </section>
@endsection
