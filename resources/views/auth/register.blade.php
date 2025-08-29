<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Custom CSS -->
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
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
            <form class="login-form" method="POST" action="{{ route('register') }}">
                @csrf

                <h2 class="login-title">Register Here to join the African family 👋</h2>
                <div class="divider"><span>or</span></div>

                <x-register-field label="Email" name="email" type="email" placeholder="Enter your email" />
                <x-register-field name="username" label="Username" placeholder="Username" />
                <x-register-field label="Password" name="password" type="password" placeholder="Enter your password" />
                <x-register-field label="Confirm Password" name="password_confirmation" type="password" placeholder="Confirm your password" />
                <x-register-field name="phone" type="tel" label="Phone Number" placeholder="Enter your phone number" />
                <x-register-field name="dob" type="date" label="Date of Birth" placeholder="Date of Birth" />
                <div class="form-options">
                    <x-register-field name="gender" type="select" label="Gender" placeholder="Gender"
                        :options="[
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other',
                            'prefer_not_to_say' => 'Prefer not to say'
                        ]" />


                        <x-register-field name="language" type="select" label="Language"
                        :options="[
                            'en' => 'English (en)',
                            'es' => 'Spanish (es)',
                            'fr' => 'French (fr)',
                            'de' => 'German (de)',
                            'zh' => 'Chinese (zh)',
                            'ja' => 'Japanese (ja)',
                            'ru' => 'Russian (ru)',
                            'ar' => 'Arabic (ar)',
                            'pt' => 'Portuguese (pt)',
                            'it' => 'Italian (it)'
                        ]" value="en" />

                            <x-register-field
                                        name="location"
                                        type="select"
                                        label="Country"
                                        placeholder="Country"
                                        :options="$countries"
                            />
                 </div>
                <div class="options-row">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember Me
                    </label>
                    <a href="#">Forgot password?</a>
                </div>

                <div class="submit-container">
                    <input type="submit" class="login-button" value="Register Here" />
                </div>

                <p class="signup-prompt">
                    Don’t have an account? <a href="{{ route('login') }}">Sign In</a>
                </p>
            </form>
        </div>
         <div class="login-image">
             <img src="{{asset('login-img.jpg') }}" alt="Login Illustration" />
        </div>
    </div>


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

