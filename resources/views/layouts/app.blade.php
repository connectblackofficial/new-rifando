<html lang="pt-br">

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

    <!-- Bootstrap CSS -->
    <link href="{{cdnAsset("build/site.css")}}" rel="stylesheet">


    <title><?php echo @$data['social']->name; ?> @yield('title')</title>

    <meta name="facebook-domain-verification" content="<?php echo @$data['social']->verify_domain_fb; ?>"/>

    <?php echo @$data['social']->pixel; ?>
    <script>
        var user_phone = "<?= getSiteOwnerUser()->telephone ?>"
    </script>

    <script src="{{cdnAsset("js/site-header-bundle.min.js")}}"></script>
</head>

<body id="{{Route::currentRouteName()}}">
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
                    @if (@$data['social']->logo)
                        <img src="{{ imageAsset( @$data['social']->logo) }}" alt="" width="100">
                    @else
                        Agency
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
                                                                                   style="font-size: 35px;"></i>
                </button>

                @if (env('IS_TREVO_MINAS'))
                    <a href="https://www.instagram.com/{{ @$data['social']->instagram }}" target="_blank"
                       style="text-decoration: none; font-size: 20px; color: #CF235F"><i
                                class="fab fa-instagram"></i></a>
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
                            <img src="{{imageAsset(@$data['social']->logo)}}" alt=""
                                 class="app-brand img-fluid">
                        @else
                            Agency Rauen
                        @endif
                    </a>
                    <div class="app-header-mobile">
                        <button type="button"
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
    <div class="col-12 rifas">
        <div class="container app-main" id="app-main">
            @yield('content')
        </div>
    </div>
</div>

<script>
    document.getElementById('telephone').addEventListener('input', function (e) {
        var aux = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
        e.target.value = !aux[2] ? aux[1] : '(' + aux[1] + ') ' + aux[2] + (aux[3] ? '-' + aux[3] : '');
    });

    document.getElementById('telephone1').addEventListener('input', function (e) {
        var aux = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
        e.target.value = !aux[2] ? aux[1] : '(' + aux[1] + ') ' + aux[2] + (aux[3] ? '-' + aux[3] : '');
    });

    function loading() {
        var el = document.getElementById('loadingSystem');
        el.classList.toggle("d-none");
    }
</script>
<script src="{{cdnAsset("js/site-footer-bundle.min.js")}}"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

</body>

</html>
