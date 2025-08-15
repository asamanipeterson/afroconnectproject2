<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
<div class="container">
    @include('layouts.topbar')
    <div class="main-layout">

            @include('layouts.sidebar')


            @include('layouts.main_content')


            @include('layouts.rightbar')
        </div>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
 <script src="{{ asset('js/rightbar.js') }}"></script>
<script src="{{ asset('js/show.js') }}"></script>
 @yield('scripts')
</body>
</html>
