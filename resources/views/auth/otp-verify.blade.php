<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); */
            background:#000;
            padding: 20px;
            font-family: 'Lexend', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .otp-container {
            max-width: 450px;
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            padding: 50px;
            color: #fff;
            text-align: center;
        }

        .otp-title {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .otp-subtitle {
            font-size: 1rem;
            margin-bottom: 2rem;
            color: #bbb;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            text-align: left;
        }

        /* New styling for the OTP input container */
        .otp-input-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .otp-input {
            width:100%;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            border: 1px solid #444;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transition: all 0.3s ease;
        }

        .otp-input:focus {
            border-color: #6a11cb;
            outline: none;
            box-shadow: 0 0 0 2px rgba(106, 17, 203, 0.2);
        }

        .timer-display {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
            margin-top: 1rem;
        }

        .submit-button, .resend-button, .otp-input-group {
            width: 100%;
        }

        .submit-button {
            padding: 1rem;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(106, 17, 203, 0.2);
        }

        .submit-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(106, 17, 203, 0.3);
        }

        .invalid-feedback {
            color: #ff6b6b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .resend-button {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid #6a11cb;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0.7;
            margin-top: 10px;
        }

        .resend-button:disabled {
            cursor: not-allowed;
            opacity: 0.3;
        }

        .resend-button:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2 class="otp-title">OTP Verification</h2>
        <p class="otp-subtitle">A 6-digit code has been sent to your email.</p>

        @if (session('success'))
            <div style="color: #4CAF50; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        <form id="otp-form" method="POST" action="{{ route('otp.verify.logic') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="otp">Enter OTP</label>
                <div class="otp-input-group">
                    <input type="text" name="otp" id="otp" class="otp-input" maxlength="6" required>
                </div>
                @error('otp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="timer-display">
                <span id="countdown">5:00</span>
            </div>

            <button type="submit" class="submit-button mt-4">Verify Code</button>
        </form>

        <form id="resend-form" method="POST" action="{{ route('otp.resend.logic') }}">
            @csrf
            <button type="submit" class="resend-button" id="resend-button" disabled>Resend OTP</button>
        </form>

    </div>

    <script>
        const countdownEl = document.getElementById('countdown');
        const submitButton = document.querySelector('.submit-button');
        const resendButton = document.getElementById('resend-button');
        const otpInput = document.getElementById('otp');
        const otpForm = document.getElementById('otp-form');
        let totalTime = 300; // 5 minutes in seconds

        function updateCountdown() {
            let minutes = Math.floor(totalTime / 60);
            let seconds = totalTime % 60;

            minutes = minutes < 5 ? '0' + minutes : minutes;
            seconds = seconds < 5 ? '0' + seconds : seconds;

            countdownEl.textContent = `${minutes}:${seconds}`;

            if (totalTime <= 0) {
                clearInterval(countdownInterval);
                submitButton.disabled = true;
                resendButton.disabled = false;
                resendButton.textContent = 'Resend OTP';
                countdownEl.textContent = '00:00';
            }
            totalTime--;
        }

        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown();

        // New code to auto-submit the form
        otpInput.addEventListener('input', function() {
            if (this.value.length === 6) {
                otpForm.submit();
            }
        });
    </script>
</body>
</html>
