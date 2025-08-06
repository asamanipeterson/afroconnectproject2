<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
    @section("styles")
        <link rel="stylesheet" href="{{ asset('css/apps.css') }}">
        <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main_content.css') }}">
        <link rel="stylesheet" href="{{ asset('css/rightbar.css') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">

        <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container">
    @include('layouts.topbar')
    <div class="main-layout">

            @include('layouts.sidebar')


            @include('layouts.main_content')


            @include('layouts.rightbar')
        </div>
</div>
 <script src="{{ asset('js/topbar.js') }}"></script>
 <script src="{{ asset('js/sidebar.js') }}"></script>
 <script src="{{ asset('js/main_content.js') }}"></script>
 <script src="{{ asset('js/rightbar.js') }}"></script>
<script src="{{ asset('js/show.js') }}"></script>
 @yield('scripts')
</body>
</html>
