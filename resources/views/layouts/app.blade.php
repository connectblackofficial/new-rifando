<html lang="pt-br">
<?php

$siteConfig = getSiteConfig();
?>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Language" content="pt-br">

    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <meta name="color-scheme" content="light only">
    <meta name="X-DarkMode-Default" value="false"/>

    @yield('ogContent')
    @yield('scripts-top')
    <script>
        var ROUTES = <?= getSiteJsRoutes() ?>;
        var CDN_URL = "<?= cdnAsset() ?>";
    </script>
    <style>
        :root {
            --brand-color: #671392;
            --btn-free-color: #198754;
            --btn-reserved-color: #ffc107;
            --btn-paid-color: #0dcaf0;
            --brand-bg-color: #000;
            --secondary-bg-color: #020f1e;
            --secondary-bg-text-color: #fff;

        }

    </style>

    <!-- Bootstrap CSS -->
    <link href="{{cdnAsset("build/site.css")}}" rel="stylesheet">

    @if(isset($siteConfig['name']))
        <title>{{$siteConfig['name']}} @yield('title')</title>
    @else
        <title>@yield('title')</title>
    @endif


    @if(isset($siteConfig['verify_domain_fb']) && !empty($siteConfig['verify_domain_fb']))
        <meta name="facebook-domain-verification" content="<?php echo $siteConfig->verify_domain_fb; ?>"/>
            <?php echo $siteConfig->pixel; ?>
    @endif

    <script>
        var user_phone = "<?= getSiteOwnerUser()->telephone ?>"
    </script>

    <script src="{{cdnAsset("js/site-header-bundle.min.js")}}"></script>

</head>

<body id="pg-{{routeAsDivId()}}-route">
@section('sidebar')
@show
<?php
$subDomain = explode('.', request()->getHost());
?>
<div id="loadingSystem" class="d-none"></div>
<nav class="navbar navbar-expand-lg  fixed-top px-0 py-3 ">

    <div class="container header-menu" style="justify-content:space-evenly;align-items: center;">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <a class="" href="{{ route('inicio') }}">
                    @if ($siteConfig->logo)
                        <img src="{{ imageAsset( $siteConfig->logo) }}" alt="" width="100">
                    @else
                        Site
                    @endif
                </a>
            </div>

            <div>

                <a href="#" data-bs-toggle="modal" data-bs-target="#consult-order-modal"
                   style="text-decoration: none; font-size: 15px; color: #fff">
                    <i class="bi bi-cart-check"
                       style="margin-top: 10px;font-size: 30px;color: rgb(180, 180, 180) !important; opacity: 1;"></i>
                </a>
                <button type="button" aria-label="Menu" class="btn btn-link text-white" data-bs-toggle="modal"
                        data-bs-target="#mobileMenu" style="margin-top: -10px;"><i class="bi bi-filter-right"
                                                                                   style="font-size: 35px;"></i>
                </button>

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
                        @if ($siteConfig->logo)
                            <img src="{{imageAsset($siteConfig->logo)}}" alt=""
                                 class="app-brand img-fluid">
                        @else
                            Site
                        @endif
                    </a>
                    <div class="app-header-mobile">
                        <button type="button"
                                class="btn btn-link text-white menu-mobile--button pe-0 font-lgg"
                                data-bs-dismiss="modal" aria-label="Fechar"><i class="bi bi-x-circle"></i></button>
                    </div>
                </div>
            </header>
            <div class="modal-body primary-bg-color">
                <div class="container container-600">
                    <nav class="nav-vertical nav-submenu font-xs mb-2">
                        <ul>
                            <li><a class="text-white" alt="Página Principal" href="/"><i
                                            class="icone bi bi-house"></i><span>Início</span></a></li>
                            @if ($siteConfig->enable_affiliates==1)
                                <li>
                                    <a class="text-white" alt="Área de Afiliados"
                                       href="{{ route('afiliado.home') }}"><i
                                                class="icone bi bi-cash-coin"></i><span>Área de
                                                Afiliados</span></a>
                                </li>
                            @endif

                            <li>
                                <a class="text-white" alt="Sorteios" href="{{route("sorteios")}}">
                                    <i class="icone bi bi-ticket"></i><span>Sorteios</span>
                                </a>
                            </li>

                            <li>
                                <a class="text-white" alt="Meus Números" data-bs-toggle="modal"
                                   data-bs-target="#consult-order-modal"><i
                                            class="icone bi bi-receipt"></i>
                                    <span>Meus números</span>
                                </a>
                            </li>

                            <li><a alt="Termos de uso" class="text-white" href="{{ route('politica') }}"><i
                                            class="icone bi bi-blockquote-right"></i><span>Política de Privacidade</span></a>
                            </li>
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

</div>

<!-- Modal  consultar -->
<?= modal("consult-order-modal", "CONSULTAR RESERVAS", view("site.orders.consult-order-modal")->render()) ?>

@if ($siteConfig->group_whats != null)
    <a href="{{ $siteConfig->group_whats }}" class="botao-flutuante" target="_blank">
        <i style="margin-top:8px" class="fa fa-whatsapp"></i>&nbsp; GRUPO
    </a>
@endif


@if ($siteConfig->instagram != null)
    <a href="https://www.instagram.com/{{ $siteConfig->instagram }}" class="botao-flutuante-insta" target="_blank">
        <i style="margin-top:8px" class="fab fa-instagram"></i>
    </a>
@endif

<div class="row justify-content-center" style="max-width: 100vw !important;">
    <div class="col-12 rifas">
        <div class="container app-main" id="app-main">
            @yield('content')
        </div>
    </div>
</div>
@yield('modals')
<script>

</script>
@include("site.layouts.modal-url")
<script src="{{cdnAsset("js/site-footer-bundle.min.js")}}"></script>
<script>
    $(document).ready(function () {
        initSitePg();
    })
</script>
@yield('scripts-footer')
</body>

</html>
