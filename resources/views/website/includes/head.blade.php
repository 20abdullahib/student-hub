{{-- header style --}}
<link href="{{asset('assets/Website/css/header/bootstrap.min.css')}}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

<link href="{{ asset('assets/Website/css/header/header_animate.css') }}" rel="stylesheet">
<link href="{{ asset('assets/Website/css/header/bootsnav.css') }}" rel="stylesheet">
<link href="{{ asset('assets/Website/css/header/header_custom.css') }}" rel="stylesheet">


{{-- body style --}}
<link rel="stylesheet" href="{{asset('assets/Website/css/body/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/Website/css/body/hero_custom.css')}}">
<link rel="stylesheet" href="{{asset('assets/Website/css/body/middel_custom.css')}}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>


{{-- footer style --}}

<link rel="stylesheet" href="{{asset('assets/Website/css/footer/footer_custom.css')}}">

{{-- Search suggestions CSS --}}
<style>
.suggestions-container {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    max-height: 320px;
    overflow-y: auto;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    z-index: 1050 !important;
    width: 100%;
    top: 100%;
    left: 0;
    margin-top: 2px;
    display: none; /* Hidden by default */
}

.suggestions-container .suggestion-item {
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f0f0f0;
    padding: 12px 16px;
}

.suggestions-container .suggestion-item:last-child {
    border-bottom: none;
}

.suggestions-container .suggestion-item:hover {
    background-color: #f8f9fa !important;
    transform: translateX(2px);
}

.suggestions-container .suggestion-item:active {
    background-color: #e9ecef !important;
}

/* Smooth scrollbar */
.suggestions-container::-webkit-scrollbar {
    width: 6px;
}

.suggestions-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.suggestions-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.suggestions-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Specific styling for resource suggestions container */
#resource-suggestions-container {
    background-color: white !important;
    border: 1px solid #e0e0e0 !important;
    border-radius: 8px !important;
    z-index: 9999 !important;
    position: absolute !important;
    width: 100% !important;
    top: calc(100% + 2px) !important;
    left: 0 !important;
}

/* Animation for showing/hiding */
.suggestions-container.show {
    display: block;
    animation: fadeInDown 0.2s ease-out;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Ensure parent container has relative positioning */
.search-container {
    position: relative !important;
}
</style>