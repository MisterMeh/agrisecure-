<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your 2FA Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .code-box {
            background-color: #e9ecef;
            font-size: 28px;
            font-weight: bold;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            letter-spacing: 3px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Your Authentication Code</div>
        <p>Hello,</p>
        <p>Please use the following code to complete your login. This code is valid for 10 minutes.</p>
        <div class="code-box">
            {{ $code }}
        </div>
        <p>If you did not request this code, you can safely ignore this email.</p>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
