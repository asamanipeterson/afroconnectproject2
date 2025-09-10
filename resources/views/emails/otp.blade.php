<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
        body {
            font-family: 'Lexend', sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .header {
            background: #2d3748;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
            text-align: center;
            color: #4a5568;
        }
        .otp-code {
            font-size: 48px;
            font-weight: 700;
            color: #2d3748;
            background-color: #f7fafc;
            display: inline-block;
            padding: 15px 30px;
            margin: 20px 0;
            border-radius: 8px;
            letter-spacing: 5px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
        }
        .timer {
            font-size: 16px;
            color: #718096;
            margin-top: 10px;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .action-button {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            color: #ffffff;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            box-shadow: 0 5px 15px rgba(106, 17, 203, 0.2);
        }
        .footer {
            background-color: #f7fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>AfroConnect OTP Verification</h2>
        </div>
        <div class="content">
            <p style="font-size: 18px; margin-bottom: 20px;">Hello,</p>
            <p style="font-size: 16px;">Your One-Time Password (OTP) is:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p class="timer">Please use this code within the next minutes.</p>
            <p style="font-size: 14px; color: #718096; margin-top: 25px;">
                If you did not request this, please ignore this email.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} AfroConnect. All rights reserved.
        </div>
    </div>
</body>
</html>
