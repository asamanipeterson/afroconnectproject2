<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Custom CSS -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Favicon -->
    {{-- <link rel="shortcut icon" href="" type="image/x-icon"> --}}
     <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>
<body>
    <div class="login-container">
        <div class="login-form-container">
            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <h2 class="login-title">Welcome back 👋</h2>

                {{-- <button type="button" class="apple-login-button">
                    <i class="bi bi-apple apple-logo"></i>
                    Log In with Apple
                </button> --}}

                {{-- <div class="divider"><span>or</span></div> --}}

                <x-login-field label="Email" name="email" type="email" placeholder="Enter your email" />
                <x-login-field label="Password" name="password" type="password" placeholder="Enter your password" />

                <div class="options-row">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember Me
                    </label>
                    <a href="#">Forgot password?</a>
                </div>

                <div class="submit-container">
                    <input type="submit" class="login-button" value="Log In" />
                </div>

                <p class="signup-prompt">
                    Don’t have an account? <a href="{{ route('register') }}">Sign Up</a>
                </p>
            </form>
        </div>
        <div class="cards-container">
            <div class="card card-1">
                <img src="{{ asset('login.webp') }}" alt="Card Image 1">
                <h3>Connecting people</h3>
            </div>
            <div class="card card-2">
                <img src="{{ asset('login2.webp') }}" alt="Card Image 2">
                <h3>Sharing experiences</h3>
            </div>
            <div class="card card-3">
                <img src="{{ asset('login3.jpg') }}" alt="Card Image 3">
                <h3>Building communities</h3>
            </div>
            <div class="card card-4">
                <img src="{{ asset('celebrate.webp') }}" alt="Card Image 4">
                <h3>Celebrating culture</h3>
            </div>
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword(icon) {
            const input = icon.closest('.input-with-icon').querySelector('[data-password]');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
