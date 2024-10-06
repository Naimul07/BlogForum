<!-- resources/views/emails/otp.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f7;
            color: #51545e;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .email-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #ffffff;
            color: #000000;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            font-size: 24px;
            margin: 0;
        }

        .email-body {
            padding: 30px;
            line-height: 1.6;
            font-size: 16px;
        }

        .email-body h2 {
            font-size: 20px;
            color: #333333;
            margin-top: 0;
        }

        .email-body p {
            margin: 0 0 16px;
        }

        .otp-code {
            background-color: #f4f4f7;
            color: #333333;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            letter-spacing: 2px;
            border: 1px dashed #3869d4;
        }

        .email-footer {
            background-color: #f4f4f7;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b6e76;
        }

        a {
            color: #3869d4;
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>OTP Verification</h1>
        </div>
        <div class="email-body">
            <h2>Hello,</h2>
            <p>We received a request to verify your email address for your account.</p>
            <p>Your One-Time Password (OTP) is:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>Please enter this code to complete your email verification. This OTP is valid for a 10 minutes.</p>
            <p>If you didn't request this email, you can safely ignore it.</p>
            <p>Thank you for using our service!</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
