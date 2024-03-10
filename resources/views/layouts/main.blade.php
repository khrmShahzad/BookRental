<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Rental | @yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{asset('webassets/img/favicon.png')}}" rel="icon">
    <link href="{{asset('webassets//img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <link href="{{asset('webassets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('webassets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->

    <link href="{{asset('webassets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('webassets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet" />
    <link href="{{asset('webassets/vendor/aos/aos.css')}}" rel="stylesheet" />
    <link href="{{asset('webassets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet" />
    <link href="{{asset('webassets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="{{asset('webassets/css/main.css')}}" rel="stylesheet" />

</head>

<body>

@include('top-bar')

@include('header')

@include('notifications')
@yield('content')

@include('footer')

<a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="{{asset('webassets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('webassets/vendor/aos/aos.js')}}"></script>
<script src="{{asset('webassets/vendor/glightbox/js/glightbox.min.js')}}"></script>
<script src="{{asset('webassets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
<script src="{{asset('webassets/vendor/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('webassets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
{{--<script src="{{asset('webassets/vendor/php-email-form/validate.js')}}"></script>--}}

<!-- Template Main JS File -->
<script src="{{asset('webassets/js/main.js')}}"></script>

</body>

</html>
