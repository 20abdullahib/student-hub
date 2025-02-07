<!DOCTYPE html>
<html lang="en">

<head>
    @include('dashboard.includes.head')
    <title>@yield('title')</title>
    @yield('custom-css')
    @yield('custom-meta')
</head>

<body>
    <!-- Navbar -->
    @include('dashboard.includes.mobile-navbar')
    @include('dashboard.includes.sidebar')

    <main class="content">
        <!-- Header -->
        @include('dashboard.includes.header')

        <!-- Alerts -->
        @include('dashboard.includes.alerts')

        <!-- Content -->
        @yield('content')

        <!-- Footer -->
        @include('dashboard.includes.footer')
    </main>

    <!-- Scripts -->
    @include('dashboard.includes.scripts')

    @yield('custom-scripts')
</body>

</html>
