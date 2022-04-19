<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from uiwebsoft.com/justlog/login-nine/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Aug 2020 00:36:10 GMT -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>JustLog - Login</title>
    <!-- External CSS -->
    <link type="text/css" rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="../assets/fonts/font-awesome/css/font-awesome.min.css">

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/fev.png">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="css/app.css">
    <link type="text/css" rel="stylesheet" href="css/login-nine.css">

</head>

<body>

    <!-- Loader -->
    <div class="loader">
        <div class="loader_div"></div>
    </div>

    <!-- Login page -->
    <div class="login_wrapper">
        <div class="row no-gutters">

            <div class="col-md-6 mobile-hidden">
                <div class="login_left">
                    <div class="login_left_img"><img src="assets/images/login-bg.jpg" alt="login background"></div>
                </div>
            </div>
            <div class="col-md-6 bg-white" style="z-index: 1;">
                <div class="login_box">
                    <a href="#" class="logo_text">
                        <span>JL</span> Just Log
                    </a>
                    <div class="login_form">
                        <div class="login_form_inner">
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="email" name="email" class="input-text @error('email') is-invalid @enderror" placeholder="Email Address" value="{{ old('email') }}">
                                    <i class="fa fa-envelope"></i>
                                    @error('email')
                                        <span class="invalid-feedback text-start" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" name="Password" class="input-text @error('email') is-invalid @enderror" placeholder="Password">
                                    <i class="fa fa-lock"></i>
                                    @error('password')
                                        <span class="invalid-feedback text-start" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="checkbox clearfix">
                                    <div class="form-check checkbox-theme ps-0">
                                        <input class="form-check-input" type="checkbox" {{ old('remember') ? 'checked' : '' }} name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">Forgot Password</a>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn-md btn-theme btn-block">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /. Login page -->


    <!-- External JS libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Custom JS Script -->
    <script type="text/javascript">
        var $window = $(window);

        // :: Preloader Active Code
        $window.on('load', function () {
            $('.loader').fadeOut('slow', function () {
                $(this).remove();
            });
        });

    </script>

</body>

<!-- Mirrored from uiwebsoft.com/justlog/login-nine/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Aug 2020 00:36:12 GMT -->

</html>
