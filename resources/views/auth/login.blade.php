<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriSecure Login</title>
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> --}}
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
                <span class="d-flex justify-content-center mb-3 mt-3"><h2>Welcome to <span class="font-weight-bold">AgriSecure</span></h2></span>

                
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

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <i class="fas fa-envelope login-color"></i>
                        <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock login-color"></i>
                        <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                    </div>

                    <!-- CAPTCHA -->
                    <!-- <div class="form-group">
                        <div class="captcha-container">
                            <span id="captcha-img">{!! captcha_img('flat') !!}</span>
                            <i class="fas fa-sync-alt reload" id="reload-captcha"></i>
                        </div>
                        <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha" required>
                    </div> -->
                    <div class="form-group captcha-group-container">
    
    <div id="captcha-section" >
        <div class="captcha-container-inline">
            
            <span id="captcha-img">{!! captcha_img() !!}</span>
            
            <input id="captcha" type="text" class="form-control captcha-input" placeholder="Enter Captcha" name="captcha">
        </div>
    </div>
</div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label" for="remember_me">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <div class="forgot-password">
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-login">Login</button>
                    
                    <p class="terms-text">By signing in you agree to the terms and conditions</p>
                </form>

                <div class="register-link">
                    New member, <a href="{{ route('register') }}">REGISTER HERE!</a>
                </div>
            </div>
        </div>
    </div>

    
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script type="text/javascript">
        $('#reload-captcha').click(function () {
            $.ajax({
                type: 'GET',
                url: '{{ route("my-captcha.reload") }}',
                success: function (data) {
                    $("#captcha-img").html(data.captcha);
                }
            });
        });

        $('#reload-captcha').click(function () {
        $.ajax({
            type: 'GET',
            url: '{{ route("my-captcha.reload") }}',
            success: function (data) {
                $("#captcha-img").html(data.captcha);
            }
        });
    });

   
    $('#captcha-checkbox').change(function() {
        if(this.checked) {
            $('#captcha-section').slideDown('fast');
            $('#captcha').attr('required', true);
        } else {
            $('#captcha-section').slideUp('fast');
            $('#captcha').attr('required', false);
        }
    });
    </script>
</body>
</html>
