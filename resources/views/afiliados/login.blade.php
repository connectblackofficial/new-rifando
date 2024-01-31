<!DOCTYPE html>
<html lang="en">
<?php

$siteConfig = getSiteConfig();
?>
<head>
    <title><?php echo $siteConfig->name; ?> @yield('title')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ cdnImageAsset('icons/favicon.ico') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ cdnAsset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ cdnAsset('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ cdnAsset('vendor/animate/animate.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ cdnAsset('vendor/css-hamburgers/hamburgers.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ cdnAsset('vendor/select2/select2.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ cdnAsset('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ cdnAsset('css/main.css') }}">
    <!--===============================================================================================-->
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ cdnImageAsset('img-01.png') }}" alt="IMG">
                </div>

                <form class="login100-form validate-form" method="POST" action="{{ route('afiliado.login') }}">
                    @csrf
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $error }}
                            </div>
                        @endforeach
                    @endif


                    <span class="login100-form-title">
                        Login Afiliado <br>
                        <a href="{{ route('inicio') }}">
                            @if ($siteConfig->logo)
                                <img src="{{imageAsset($siteConfig->logo)}}" alt=""
                                    width="100" height="50">
                            @else
                                Site
                            @endif
                        </a>
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="email" value="{{ old('email') }}" name="email"
                            placeholder="Email" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="password" placeholder="Senha" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>

                    <div class="text-center p-t-12">
                        <a class="txt2" href="{{ route('afiliado.cadastro') }}">
                            Cadastre-se como afiliado
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>

                    <div class="text-center p-t-136">
                        {{-- <a class="txt2" href="#">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>




    <!--===============================================================================================-->
    <script src="{{ cdnAsset('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ cdnAsset('vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ cdnAsset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ cdnAsset('vendor/select2/select2.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ cdnAsset('vendor/tilt/tilt.jquery.min.js') }}"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <!--===============================================================================================-->
    <script src="{{ cdnAsset('js/main.js"') }}></script>

</body>

</html>
