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
            background: #000;
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

        .otp-input-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .otp-input {
            width: 100%;
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

        .submit-button, .resend-button {
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
                <div class="otp-input-container">
                    <input type="text" id="otp-1" class="otp-input" maxlength="1" required>
                    <input type="text" id="otp-2" class="otp-input" maxlength="1" required>
                    <input type="text" id="otp-3" class="otp-input" maxlength="1" required>
                    <input type="text" id="otp-4" class="otp-input" maxlength="1" required>
                    <input type="text" id="otp-5" class="otp-input" maxlength="1" required>
                    <input type="text" id="otp-6" class="otp-input" maxlength="1" required>
                </div>
                <input type="hidden" name="otp" id="hidden-otp-input">
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
        const otpInputs = document.querySelectorAll('.otp-input-container input');
        const hiddenOtpInput = document.getElementById('hidden-otp-input');
        const otpForm = document.getElementById('otp-form');
        let totalTime = 300; // 5 minutes in seconds

        function updateCountdown() {
            let minutes = Math.floor(totalTime / 60);
            let seconds = totalTime % 60;

            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

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

        // Loop through each input field to add event listeners
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // If a user types, move to the next box
                if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                updateHiddenInput();
            });

            input.addEventListener('keydown', (e) => {
                // Handle backspace to move to the previous input field
                if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        // The key part: Handle the paste event on the FIRST input field only
        otpInputs[0].addEventListener('paste', (e) => {
            e.preventDefault(); // Stop the default paste behavior
            const pasteData = e.clipboardData.getData('text').trim();

            // Check if pasted data is a valid 6-digit number
            if (pasteData.length === 6 && /^\d+$/.test(pasteData)) {
                for (let i = 0; i < 6; i++) {
                    otpInputs[i].value = pasteData.charAt(i);
                }
                updateHiddenInput();
                otpForm.submit(); // Auto-submit after filling
            }
        });

        function updateHiddenInput() {
            let combinedValue = '';
            otpInputs.forEach(input => {
                combinedValue += input.value;
            });
            hiddenOtpInput.value = combinedValue;

            if (combinedValue.length === 6) {
                // Auto-submit after all inputs are filled by typing
                otpForm.submit();
            }
        }
    </script>
</body>
</html>
