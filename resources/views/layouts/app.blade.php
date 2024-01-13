<!-- Stored in resources/views/layouts/master.blade.php -->

<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('/css/app-original-2.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Language" content="pt-br">

    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <meta name="color-scheme" content="light only">
    <meta name="X-DarkMode-Default" value="false" />

    @yield('ogContent')


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- Fontawesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- jQuery 1.8 or later, 33 KB -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>



    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    <!-- Fotorama from CDNJS, 19 KB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>

    <!--<script defer src="{{ mix('js/app.js') }}"></script>
    <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>-->

    <title><?php echo @$data['social']->name; ?> @yield('title')</title>

    <meta name="facebook-domain-verification" content="<?php echo @$data['social']->verify_domain_fb; ?>" />

    <?php echo @$data['social']->pixel; ?>


    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        const mp = new MercadoPago("<?php echo @$data['social']->key_pix_public; ?>");
    </script>

    <style>
        body{
            /* min-height: 105vh; */
        }
        @media (max-width: 768px) {
            .meus-numeros {
                margin-left: 50px !important;
            }

            .header-menu {
                justify-content: space-between !important;
            }
        }

        @media screen and (max-width: 768px) {
        .app-main {
            /* margin-top: 90px !important; */
            margin-top: 20px !important;
            position: absolute;
            z-index: 9999 !important;
        }

        .swal2-container{
            z-index: 99999;
        }
    }

        .app-main {
            margin-bottom: 0px !important;
            min-height: 100vh;
        }

        #loadingSystem {
            background: rgba(206, 206, 206, 0.5) url("../../images/loading.gif") no-repeat scroll center center;
            background-size: 150px 150px;
            height: 100%;
            left: 0;
            overflow: visible;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999999;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/menu2.css') }}">
</head>

    <link rel="manifest" href="/manifest.json">
<script type="text/javascript" src="sw.js"></script>
<style>
    body {
        background: #000 !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script>
    $(function(e) {
        // if (isIOS()) {
        //     $('#app-main').attr('style', 'margin-top: 100px !important');
        // }
    })

    function isIOS() {
        var ua = navigator.userAgent.toLowerCase();

        //Lista de dispositivos que acessar
        var iosArray = ['iphone', 'ipod'];

        var isApple = false;

        if (ua.includes('iphone') || ua.includes('ipod')) {
            isApple = true
        }

        return isApple;
    }

    function duvidas() {
        window.open('https://api.whatsapp.com/send?phone={{ $user->telephone }}', '_blank');
    }

    function verRifa(route) {
        window.location.href = route
    }
</script>


<style>
        .duvida {
            background-color: #ffffff5e;
            border-radius: 10px;
            height: 60px;
            align-items: center;
            justify-content: center;
            margin-top: 7px;
            cursor: pointer;
        }

        .icone-duvidas {
            width: 50px;
            justify-content: center;
            align-items: center;
            background-color: #b9b9b9;
            height: 35px;
            border-radius: 10px;
            text-align: center;
            font-size: 20px;
        }

        .text-duvidas {
            display: flex !important;
            flex-direction: column;
            justify-content: center
        }

        .f-15 {
            font-size: 15px;
        }

        .f-12 {
            font-size: 12px;
        }

        .data-sorteio {
            /* float: right; */
            padding-right: 10px;
            font-weight: thin;
            text-align: center;
            /* margin-top: 10px; */
            color: #000;
        }

        .rifas.dark {
            background: #383838;
        }

        .app-title.dark h1 {
            color: #fff;
        }

        .app-title-desc.dark {
            color: #fff;
        }

        .card-rifa-destaque.dark {
            background: #222222;
        }

        .title-rifa-destaque.dark h1 {
            color: #fff;
        }

        .title-rifa-destaque.dark p {
            color: #fff;
        }

        .card-rifa.dark {
            background: #222222;
        }

        .text-duvidas.dark h6 {
            color: #fff;
        }

        .text-duvidas.dark p {
            color: #fff !important;
        }

        .data-sorteio.dark {
            color: #fff !important;
        }

        .app-title.dark {
            color: #fff;
        }
    </style>

<style>
    

    @media only screen and (-webkit-min-device-pixel-ratio: 1) {

        ::i-block-chrome,
        .app-main {
            margin-top: 100px !important;
        }
    }



    .app-main a {
        text-decoration: none;
    }

    .app-main a:hover {
        text-decoration: none;
    }

    .app-title {
        display: flex;
        align-items: self-end;
        padding-bottom: 10px;
    }

    .app-title h1 {
        color: rgba(0, 0, 0, .9);
        padding-right: 5px;
        font-weight: 600;
        font-size: 1.3em;
        margin: 0;
        padding-top: 10px;
    }

    .app-title .app-title-desc {
        color: rgba(0, 0, 0, .5);
        padding-top: 6px;
        font-size: .9em;
    }


    /* *************************************************************** */
    /* Card Rifa em Destaque */
    /* *************************************************************** */
    .rifas {
        background: #e4e4e4;
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
        position: absolute;
        border-bottom-right-radius: 20px;
        border-bottom-left-radius: 20px;
        min-height: 100vh;
    }

    .rifa-dark {
        background-color: #383838;
    }

    .card-rifa-destaque .img-rifa-destaque img {
        width: 100%;
        height: 290px;
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
    }

    .card-rifa-destaque {
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
        padding-bottom: 10px;
        background: #fff;
        margin-bottom: 10px;
        border-bottom-right-radius: 20px;
        border-bottom-left-radius: 20px;
    }

    .title-rifa-destaque {
        padding-top: 5px;
        padding-left: 10px;
    }

    .title-rifa-destaque h1 {
        color: #202020;
        -webkit-line-clamp: 2 !important;
        margin-bottom: 1px;
        font-weight: 700;
        font-size: 19px;
        letter-spacing: -.2px;
    }

    .title-rifa-destaque p {
        color: rgba(0, 0, 0, .7);
        font-size: .75em;
        max-width: 96%;
        margin: 0;
    }

    /* *************************************************************** */


    /* *************************************************************** */
    /* Card Rifa Normal */
    /* *************************************************************** */
    .card-rifa img {
        width: 300px;
        border-radius: 10px;
    }

    .card-rifa {
        background: #fff;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 10px;
        display: flex
    }

    .title-rifa {
        margin-left: 15px;
        width: 100%;
    }

    .blink {
        margin-top: 5px;
        animation: animate 1.5s linear infinite;
    }



    @keyframes animate {
        0% {
            opacity: 0;
        }

        50% {
            opacity: 0.7;
        }

        100% {
            opacity: 0;
        }
    }
</style>


    
    <!-- CSS JOAO PEDRO - PASSAR PARA UM ARQUIVO CSS -->
    <style>
        .w-full {
            width: 100%;
        }

        img {
            max-width: 100%;
        }

        .rounded-md {
            border-radius: 10px;
        }
    </style>
    <style>

        :root {
            --brand-color: #671392;
        }

        .navbar {
            background-color: var(--brand-color);
        }


    </style>
    <!-- FIM -->

<body>
    @section('sidebar')
    @show

    <?php
    $subDomain = explode('.', request()->getHost());
    ?>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <div id="loadingSystem" class="d-none"></div>


    <nav class="navbar navbar-expand-lg  fixed-top px-0 py-3 ">

        <div class="container header-menu" style="justify-content:space-evenly;align-items: center;">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <a class="" href="{{ route('inicio') }}">
                        @if (@$data['social']->logo)
                            <img src="{{ asset('custom/' . @$data['social']->logo) }}" alt="" width="100">
                        @else
                            Agency Rauen
                        @endif
                    </a>
                </div>

                <div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#consultar-reservas"
                        style="text-decoration: none; font-size: 15px; color: #fff">
                        @if (env('APP_URL') == 'agencyrauen.com')
                            <span class="badge bg-success p-2" style="font-size: 10px;"><i
                                    class="fas fa-search"></i>&nbsp;MEUS NÚMEROS</span>
                        @else
                            <i class="bi bi-cart-check"
                                style="margin-top: 10px;font-size: 30px;color: rgb(180, 180, 180) !important; opacity: 1;"></i>
                        @endif
                    </a>

                    <button type="button" aria-label="Menu" class="btn btn-link text-white" data-bs-toggle="modal"
                        data-bs-target="#mobileMenu" style="margin-top: -10px;"><i class="bi bi-filter-right"
                            style="font-size: 35px;"></i></button>

                    @if (env('IS_TREVO_MINAS'))
                        <a href="https://www.instagram.com/{{ @$data['social']->instagram }}" target="_blank" style="text-decoration: none; font-size: 20px; color: #CF235F"><i class="fab fa-instagram"></i></a>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </nav>

    <menu id="mobileMenu" class="modal fade modal-fluid" tabindex="-1" aria-labelledby="mobileMenuLabel"
        style="display: none;z-index:99999" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-cor-primaria">
                <header class="app-header app-header-mobile--show">
                    <div class="container container-600 h-100 d-flex align-items-center justify-content-between">
                        <a href="/">
                            @if (@$data['social']->logo)
                                <img src="{{ asset('products/' . @$data['social']->logo) }}" alt=""
                                    class="app-brand img-fluid">
                            @else
                                Agency Rauen
                            @endif
                        </a>
                        <div class="app-header-mobile"><button type="button"
                                class="btn btn-link text-white menu-mobile--button pe-0 font-lgg"
                                data-bs-dismiss="modal" aria-label="Fechar"><i class="bi bi-x-circle"></i></button>
                        </div>
                    </div>
                </header>
                <div class="modal-body" style="background: #000 !important">
                    <div class="container container-600">
                        <nav class="nav-vertical nav-submenu font-xs mb-2">
                            <ul>
                                <li><a class="text-white" alt="Página Principal" href="/"><i
                                            class="icone bi bi-house"></i><span>Início</span></a></li>
                                @if (env('AFILIADOS'))
                                    <li><a class="text-white" alt="Área de Afiliados"
                                            href="{{ route('afiliado.home') }}"><i
                                                class="icone bi bi-cash-coin"></i><span>Área de
                                                Afiliados</span></a>
                                    </li>
                                @endif
                                <li><a class="text-white" alt="Sorteios" href="/sorteios"><i
                                            class="icone bi bi-ticket"></i><span>Sorteios</span></a></li>
                                <li><a class="text-white" alt="Meus Números" data-bs-toggle="modal"
                                        data-bs-target="#consultar-reservas"><i
                                            class="icone bi bi-receipt"></i><span>Meus números</span></a>
                                </li>
                               
                                <li><a alt="Termos de uso" class="text-white" href="{{ route('politica') }}"><i
                                            class="icone bi bi-blockquote-right"></i><span>Política de Privacidade</span></a></li>
                                </li>
                            </ul>
                        </nav>
                        <div class="text-center">
                            <a href="/login" class="btn btn-primary w-100 rounded-pill">
                                <i class="icone bi bi-box-arrow-in-right"></i>
                                Entrar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </menu>

    <div class="row  d-flex mt-5">
        <!--<a href="https://api.whatsapp.com/send/?phone=5511916059141" id="btn-ctt-whatsapp" style="margin-top: 5px;" target="_blank" class="visible"><label id="wa_msg_ctt"><i class="bi bi-whatsapp"></i></label></a>

       <a href="https://chat.whatsapp.com/EjrmnV9LpMG8D" id="btn-ctt-whatsapp" target="_blank" class="visible"><label id="wa_msg_ctt"><i class="bi bi-whatsapp"></i> GRUPO</label></a>

       @if (@$data['social']->group_whats == null)
@else
<a href="<?php echo @$data['social']->group_whats; ?>" style="right: 2px;
    bottom: 0;
    position: fixed;
    margin: 11px;  z-index: 100;  background-color: #28a745;
    padding: 8px 8px;
    font-size: 16px;
    color: #fff;
    line-height: 14px;
    border-radius: 8px 8px 8px 8px;
    text-align: center;
    font-weight: bold;" data-toggle="tooltip" data-placement="top" title="Whatsapp"><i class="bi bi-whatsapp" style="font-size: 2rem; color: #fff;"></i></a>
@endif

       <a href="https://t.me/+kseyi6M41Jh" style="right: 0;
    bottom: 0;
    position: fixed;
    margin: 11px;  z-index: 100;" data-toggle="tooltip" data-placement="top" title="Grupo Telegram"><i class="bi bi-telegram" style="font-size: 3rem; color: #2EA3D4;"></i></a>-->
    </div>

    <!-- Modal  consultar -->
    <div class="modal fade" id="consultar-reservas" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" style="z-index: 9999999;">
        <div class="modal-dialog">
            <div class="modal-content" style="border: none;">
                <div class="modal-header" style="background-color: #020f1e;">
                    <h5 class="modal-title" id="exampleModalLabel" style="color: #fff;">CONSULTAR RESERVAS</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #020f1e;">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('minhasReservas') }}" method="POST" style="display: flex;">
                                {{ csrf_field() }}
                                <input type="text" name="telephone" id="telephone"
                                    style="background-color: #fff;border: none;color: #000000;margin-right:5px;"
                                    aria-describedby="passwordHelpBlock" maxlength="15" placeholder="Celular com DDD"
                                    class="form-control" required>
                                <button type="submit" class="btn btn-danger">Buscar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .botao-flutuante {
            position: fixed;
            width: 100px;
            height: 30px;
            bottom: 200px;
            right: 10px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 5px;
            text-align: center;
            align-items: center;
            /* font-size: 30px; */
            box-shadow: 1px 1px 2px #888;
            z-index: 99999;
            text-decoration: none;
        }

        .botao-flutuante:hover {
            text-decoration: none;
        }

        .botao-flutuante-insta {
            position: fixed;
            width: 100px;
            height: 30px;
            bottom: 240px;
            right: 10px;
            background-color: #CF235F;
            color: #FFF;
            border-radius: 5px;
            text-align: center;
            align-items: center;
            /* font-size: 30px; */
            box-shadow: 1px 1px 2px #888;
            z-index: 99999;
            text-decoration: none;
        }

        .botao-flutuante-insta:hover {
            text-decoration: none;
        }
    </style>

    @if (@$data['social']->group_whats != null)
        <a href="{{ @$data['social']->group_whats }}" class="botao-flutuante" target="_blank">
            <i style="margin-top:8px" class="fa fa-whatsapp"></i>&nbsp; GRUPO
        </a>
    @endif
    
    
    @if (@$data['social']->instagram != null)
        <a href="https://www.instagram.com/{{ @$data['social']->instagram }}" class="botao-flutuante-insta" target="_blank">
            <i style="margin-top:8px" class="fab fa-instagram"></i>
        </a>
    @endif

    <div class="row justify-content-center" style="max-width: 100vw !important;">
        <div class="col-12 rifas {{ $config->tema }}">
            <div class="container app-main" id="app-main">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- @if (!env('HIDE_FOOTER'))
        @if (@$data['social']->footer == null)
            <footer class="footer"
                style="height:auto;background-color: #000;margin-top:0px!important; padding-top: 10px; padding-bottom: 10px;">
                <div class="container" style="text-align: center; padding-top: 5px;padding-bottom: 5px;">
                    <!-- Facebook -->
                    <a class="btn btn-primary" style="background-color: #2760AE;border: none;font-size: 20px;"
                        href="https://www.facebook.com/{{ @$data['social']->facebook }}" target="_blank"
                        rel="noreferrer noopener" role="button"><i class="bi bi-facebook"></i></a>
                    <!-- Instagram -->
                    <a class="btn btn-primary" style="background-color: #CF235F;border: none;font-size: 20px;"
                        href="https://www.instagram.com/{{ @$data['social']->instagram }}" target="_blank"
                        rel="noreferrer noopener" role="button"><i class="bi bi-instagram"></i></a>
                    <!-- Whatsapp -->
                    <a class="btn btn-primary" style="background-color: #25d366;border: none;"
                        href="https://api.whatsapp.com/send?phone={{ @$data['user']->telephone }}" target="_blank"
                        rel="noreferrer noopener" role="button"><i class="bi bi-whatsapp"
                            style="font-size: 20px;"></i></a>
                    @if (env('APP_NAME') == 'Laravel')
                        <img src="{{ asset('images/original.png') }}" title="Sistema Original Agency Rauen"
                            style="opacity: 0.2; float: right" width="50" alt="">
                    @endif
                    @if (env('FOOTER_CLIENTE'))
                        <br>
                        <a href="https://agencyrauen.com/" target="_blank" style="text-decoration: none"><span
                                class="text-muted" style="color: #fff!important; font-size: 12px;">Desenvolvido por Agency Rauen</span></a>
                    @endif
                </div>
            </footer>
        @else
            <footer class="footer " style="height:auto;background-color: #000;margin-top:0px!important;">
                <div class="container" style="text-align: center; padding-top: 5px;padding-bottom: 5px;">
                    <span class="text-muted" style="color: #fff!important;">{{ @$data['social']->footer }}</span>
                    @if (env('FOOTER_CLIENTE'))
                        <br>
                        <a href="https://apostanarifa.com.br/" target="_blank" style="text-decoration: none"><span
                                class="text-muted" style="color: #fff!important; font-size: 12px;">Desenvolvido por Agency Rauen</span></a>
                    @endif
                </div>
            </footer>
        @endif
    @endif --}}

    <script>
        document.getElementById('telephone').addEventListener('input', function(e) {
            var aux = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
            e.target.value = !aux[2] ? aux[1] : '(' + aux[1] + ') ' + aux[2] + (aux[3] ? '-' + aux[3] : '');
        });

        document.getElementById('telephone1').addEventListener('input', function(e) {
            var aux = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
            e.target.value = !aux[2] ? aux[1] : '(' + aux[1] + ') ' + aux[2] + (aux[3] ? '-' + aux[3] : '');
        });

        function loading() {
            var el = document.getElementById('loadingSystem');
            el.classList.toggle("d-none");
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
