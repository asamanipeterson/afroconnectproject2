<!DOCTYPE html>
<html lang="en">
<<<<<<< HEAD
@include('adminPages.head')
  <body>
    <div class="container-scroller">
     
      <!-- partial:partials/_sidebar.html -->
      {{--  @include('adminPages.sidebar')  --}}
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
       @include('adminPages.navbar')
        <!-- partial -->
        @include('adminPages.admincontent')
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
=======
  @include('adminPages.head')
  <style>
    .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover { background: #34495e; }
        .sidebar a.active { background: #3498db; }
  </style>
<body style="background-color: #f4f4f4;">
    <div class="sidebar">
        <h4 class="text-white mb-4">AfroConnect Admin</h4>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="mdi mdi-view-dashboard me-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
            <i class="mdi mdi-account-multiple me-2"></i> Users
        </a>
        <a href="" class="{{ request()->routeIs('admin.posts') ? 'active' : '' }}">
            <i class="mdi mdi-post-outline me-2"></i> Posts
        </a>
        <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
            <i class="mdi mdi-flag me-2"></i> Reports
        </a>
>>>>>>> c0d373d (admin side working left small touches)
    </div>
  <div class="container-scroller">
    <!-- Banner -->
    {{-- @include('adminPages.banner') --}}


    {{-- @include('adminPages.sidebar') --}}

    <!-- Page Body Wrapper -->
    <div class="container-fluid page-body-wrapper">
      <!-- Navbar -->
      @include('adminPages.navbar')

      <!-- Main Content -->
      @yield('content')
      @include('adminPages.admincontent')

      <!-- Footer -->
      {{-- @include('adminPages.footer') --}}
    </div>
  </div>

  <!-- Plugins JS -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
  <script src="assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
  <script src="assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <script src="assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
  <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
  <!-- Inject JS -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/misc.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- Custom JS -->
  <script src="assets/js/dashboard.js"></script>
</body>
</html>
