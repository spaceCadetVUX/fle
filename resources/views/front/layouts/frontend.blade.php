<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Ensure $seo always exists --}}
    @php
        $seo = $seo ?? [];
    @endphp
    {{-- SEO Meta Tags --}}
    @include('front.layouts.partials.seo')
    {{-- Google Analytics --}}
    @include('front.layouts.partials.ga')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Vite Assets -->
    <link rel="stylesheet" href="{{ asset('assets/css/frontend.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <script src="{{ asset('assets/js/frontend.js') }}"></script>
 
    {{-- Page specific head content --}}
    @stack('head')
</head>

<body>
<div id="smooth-wrapper">
    <div id="smooth-content">

        {{-- Navbar --}}
        @include('front.layouts.partials.navbar')

        {{-- Page content --}}
        @yield('content')

        {{-- Footer --}}
        @include('front.layouts.partials.footer')

    </div>
</div>


{{-- Scroll to Top Button --}}
<button id="scrollTop" aria-label="Scroll to top">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- GSAP -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/ScrollSmoother.min.js"></script>

{{-- Page specific scripts --}}
@stack('scripts')

</body>
</html>