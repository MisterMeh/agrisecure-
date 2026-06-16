<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriSecure 2FA Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/custom-css/front-panel.css') }}">
</head>
<body>

    <div class="login-container">
        <div class="left-panel" style="background-image: url('{{ asset('public/images/leftbox.png') }}');">
            <div class="logo-container">
                <img src="{{ asset('public/images/logo.jpg') }}" alt="AgriSecure Logo">
            </div>
        </div>

        <div class="right-panel">
            <div class="login-form-container">
                <span class="d-flex justify-content-center mb-2 mt-3"><h2>Two-Factor Verification</h2></span>
                <p class="text-center text-muted mb-4">A verification code has been sent to your email.</p>

                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('2fa.store') }}">
                    @csrf
                    <div class="form-group">
                        <i class="fas fa-shield-alt login-color"></i>
                        <input type="text" name="two_factor_code" class="form-control" placeholder="Enter 6-digit code" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-login">Verify Code</button>
                </form>

                <div class="register-link">
                    Didn't receive the code? <a href="{{ route('2fa.resend') }}">Resend</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>