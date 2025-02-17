@extends('website.layout.layout')

@section('title')
    Generation
@endsection

@section('content')
    <section class="hero-section py-md-8 py-6 snipcss-pGQdz style-4pKBU d-block" id="style-4pKBU">
        <div class="d-flex flex-wrap gap-4 justify-content-center px-5 py-lg-4 pt-4">
            @foreach ($generations as $student)
                <div class="d-flex gap-3">
                    <a class="bg-white text-center shadow-sm text-wrap rounded-4 w-100 border card-lift border"
                        style="width: 230px !important; height: 226px;">
                        <div class="p-3">
                            <img src="{{ $student->image }}" alt="{{ $student->name }}"
                                class="avatar avatar-xl rounded-circle">
                            <div class="mt-3">
                                <h3 class="mb-0 h4">{{ $student->name }}</h3>
                                <span class="text-gray-800 mt-3">{{ $student->branch->name }}</span><br>
                                <span class="text-gray-800 mt-3">{{ $student->role }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endsection
