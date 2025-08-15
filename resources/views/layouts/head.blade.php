
<head>
    <meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'AfroConnect')</title>
@yield('styles')
<link rel="stylesheet" href="{{ asset('css/apps.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="shortcut icon" href="assets/images/favicon.png" />
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

        <link rel="stylesheet" href="{{ asset('css/apps.css') }}">
        <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('css/main_content.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('css/rightbar.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('css/rightbar.css') }}"> --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
        <link rel="shortcut icon" href="assets/images/favicon.png" />
        <link rel="stylesheet" href="{{ asset('css/newlist.css') }}">
         <link rel="stylesheet" href="{{ asset('css/market.css') }}">
        <link rel="stylesheet" href="{{ asset('css/showItem.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
