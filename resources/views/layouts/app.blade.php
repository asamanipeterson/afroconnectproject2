<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
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

    {{-- Script stacks --}}
    @stack('scripts')

    {{-- JS Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>
    <script type="module" src="/js/app.js"></script>

    {{-- Custom Scripts --}}
    <script src="{{ asset('js/rightbar.js') }}"></script>
    <script src="{{ asset('js/show.js') }}"></script>

    @yield('scripts')

    {{-- Pusher LiveStream alerts --}}
    <script>
        Echo.private('user.' + {{ auth()->id() ?? 0 }})
            .listen('LiveStreamStarted', (e) => {
                alert(e.message + ': ' + e.stream.title + ' by ' + e.stream.user.username);
            })
            .listen('StreamEnded', (e) => {
                alert(e.message + ': ' + e.stream.title);
            });
    </script>

    {{-- ✅ SweetAlert Popup for Success Messages --}}
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
    </script>
    @endif

    {{-- ✅ SweetAlert Popup for Error Messages (optional) --}}
    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            showConfirmButton: true
        });
    </script>
    @endif
</body>
</html>
