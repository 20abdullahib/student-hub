{{-- Public Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

{{-- Custom Scripts --}}
<script src="{{ asset('assets/Website/scripts/bootnav.js') }}"></script>
<script src="{{ asset('assets/Website/scripts/custom-script.js') }}"></script>
<script src="{{ asset('assets/Website/scripts/handel-search-requset.js') }}"></script>

{{-- Components --}}
@foreach (glob(public_path('assets/Website/scripts/components/*.js')) as $file)
    <script src="{{ asset('assets/Website/scripts/components/' . basename($file)) }}"></script>
@endforeach
