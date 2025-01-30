<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('assets/errors/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/errors/adv-style.css') }}">
</head>

<body class="antialiased">
    <section class="vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center d-flex align-items-center justify-content-center">
                    <div>
                        {{-- <img class="img-fluid w-75" src="{{ asset('assets/errors/404.svg') }}" alt="404 not found"> --}}
                        <object id="svg-object" type="image/svg+xml"
                            data="{{ asset('assets/errors/404.svg') }}"></object>
                        <h1 class="mt-5">@yield('error-title')</h1>
                        <p class="lead my-4">@yield('message')</p>
                        <a href="{{ route('home.index') }}"
                            class="btn btn-gray-800 d-inline-flex align-items-center justify-content-center mb-4">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Back to homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        const svgObject = document.getElementById('svg-object');
        svgObject.addEventListener('load', () => {
                    // Access the SVG's internal document
                    const svgDoc = svgObject.contentDocument;

                    // Select the <text> element inside the SVG
                    const textElement = svgDoc.getElementById('text-error-svg');
                    textElement.textContent = @yield('code');
                })
    </script>
</body>

</html>
