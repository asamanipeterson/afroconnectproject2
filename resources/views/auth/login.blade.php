<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Custom CSS -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="shortcut icon" href="" type="image/x-icon">
</head>
<body>
    <div class="login-container">
        <div class="login-form-container">
            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <h2 class="login-title">Welcome back 👋</h2>

                <button type="button" class="apple-login-button">
                    <i class="bi bi-apple apple-logo"></i>
                    Log In with Apple
                </button>

                <div class="divider"><span>or</span></div>

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
         <div class="login-image">
            <img src="{{asset('login-img.jpg') }}" alt="Login Illustration" />
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
