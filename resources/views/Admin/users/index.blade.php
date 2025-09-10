<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .sidebars {
            width: 250px;
            background: #191c24;
            color: white;
            padding: 20px;
            position: fixed;
            top: 60px;
            height: 100%;
        }
        .sidebars a {
            color: white;
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebars a:hover { background: #272a32; }
        .sidebars a.active { background: #01164f; }
    </style>
    @include('adminPages.head')
</head>
<body>
    {{-- @include('adminPages.navbar') --}}

<div class="navbar">;.//l
</div>
    <div class="container-scroller">
        <div class="sidebars">
            <a href="{{ route('admin.dashboard') }}"><h4 class="text-white mb-4">AfroConnect Admin</h4></a>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="mdi mdi-view-dashboard me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" target=""  class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                <i class="mdi mdi-account-multiple me-2"></i> Users
            </a>
            <a href="" class="{{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
                <i class="mdi mdi-post-outline me-2"></i> Posts
            </a>
            <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                <i class="mdi mdi-flag me-2"></i> Reports
            </a>
        </div>
        <div class="container-fluid page-body-wrapper">


                @include('adminPages.usertable')

        </div>
    </div>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
</body>
</html>
