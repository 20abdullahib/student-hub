@extends('website.layout.layout')

@section('content')
<h1>Google Drive Files</h1>

@if($files)
    <ul>
        @foreach($files as $file)
            <li>
                <a href="{{ Storage::disk('google')->url($file) }}" target="_blank">
                    {{ basename($file) }}
                </a>
            </li>
        @endforeach
    </ul>
@else
    <p>No files found.</p>
@endif
@endsection