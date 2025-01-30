<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title')
    </title>
    @include('website.includes.head')
</head>

<body>
    @include('website.includes.header')

    <section class="body-section">
        @yield('content')
    </section>

    @include('website.includes.footer')

    @include('website.includes.scripts')
</body>

</html>
