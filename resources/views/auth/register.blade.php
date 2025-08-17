<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriSecure Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/custom-css/front-panel.css') }}">
</head>
<body>

    <div class="login-container">
        <div class="left-panel" style="background-image: url('{{ asset('images/leftbox.png') }}');">
            <div class="logo-container">
                <img src="{{ asset('images/logo.jpg') }}" alt="AgriSecure Logo">
            </div>
        </div>

        <div class="right-panel">
            <div class="login-form-container">
                <span class="d-flex justify-content-center mb-3 mt-3"><h2>Create your account</h2></span>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <i class="fas fa-user login-color"></i>
                        <input id="name" type="text" name="name" class="form-control" placeholder="Enter name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    </div>

                    <div class="form-group">
                        <i class="fas fa-envelope login-color"></i>
                        <input id="email" type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}" required autocomplete="username">
                    </div>

                    <div class="form-group">
                        <i class="fas fa-lock login-color"></i>
                        <input id="password" type="password" name="password" class="form-control" placeholder="Enter Password" required autocomplete="new-password">
                    </div>

                    <div class="form-group mb-5">
                        <i class="fas fa-lock login-color"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required autocomplete="new-password">
                    </div>
                    
                    <button type="submit" class="btn btn-login">Register</button>
                    
                    <p class="terms-text">By signing up you agree to the terms and conditions</p>
                </form>

                <div class="register-link">
                    Already a member? <a href="{{ route('login') }}">LOGIN HERE!</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>