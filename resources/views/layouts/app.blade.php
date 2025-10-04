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
@stack('scripts')
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>
    <script type="module" src="/js/app.js"></script>
@endpush
 <script src="{{ asset('js/rightbar.js') }}"></script>
<script src="{{ asset('js/show.js') }}"></script>
 @yield('scripts')

 <script>
    Echo.private('user.' + {{ auth()->id() ?? 0 }})
        .listen('LiveStreamStarted', (e) => {
            alert(e.message + ': ' + e.stream.title + ' by ' + e.stream.user.username);
        })
        .listen('StreamEnded', (e) => {
            alert(e.message + ': ' + e.stream.title);
        });
</script>
</body>
</html>
