<!-- Stored in resources/views/layouts/master.blade.php -->

<html style="height: auto;">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">


    <link href="{{ cdnAsset('css/admin.css') }}" rel="stylesheet">


    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">

    <title><?php echo @$data['social']->name; ?> @if(isset($pgTitle))
            - {{$pgTitle}}
        @endif</title>

    <style>

    </style>
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed" style="height: auto;">

<div id="loadingSystem" class="d-none"></div>
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('inicio') }}">
                    <span class="badge bg-primary">VER SITE</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link">
                    <form name="logout" action="{{ route('logout') }}" method="POST">
                        {{ csrf_field() }}
                        <span class="badge badge-warning" onclick="javascript:logout.submit()">SAIR</span>
                    </form>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: #3d3d3d">
        <!-- Brand Logo -->
        <a href="../../home" class="brand-link text-center"
           style="background: #5c5c5c; text-decoration: none">
            <span class="brand-text font-weight-light">Painel</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Painel
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link" id="home">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Home</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('profile') }}" class="nav-link" id="perfil">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Meu perfil</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{route('adminProduct')}}" class="nav-link" id="adicionar-sorteio">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Adicionar Sorteio</p>
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('mySweepstakes') }}" class="nav-link" id="meus-sorteios">
                                    <i class="far fa-clone nav-icon"></i>
                                    <p>Minhas Rifas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('clientes') }}"
                                   class="nav-link {{ request()->is('clientes*') ? 'active' : '' }}" id="clientes">
                                    <i class="fas fa-users"></i>
                                    <p>Clientes</p>
                                </a>
                            </li>
                            @if (!env('HIDE_GANHADORES'))
                                <li class="nav-item">
                                    <a href="{{ route('painel.ganhadores') }}"
                                       class="nav-link {{ request()->is('admin-ganhadores*') ? 'active' : '' }}"
                                       id="meus-sorteios">
                                        <i class="fas fa-trophy nav-icon"></i>
                                        <p>Ganhadores</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('wpp.index') }}"
                                   class="nav-link {{ request()->is('wpp-mensagens*') ? 'active' : '' }}"
                                   id="wpp-msgs">
                                    <i class="fab fa-whatsapp"></i>
                                    <p>Whatsapp mensagens</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tutoriais') }}"
                                   class="nav-link {{ request()->is('tutoriais*') ? 'active' : '' }}"
                                   id="wpp-msgs">
                                    <i class="fas fa-list"></i>
                                    <p>Tutoriais</p>
                                </a>
                            </li>
                            @if (env('AFILIADOS'))
                                <li class="nav-item">
                                    <a href="{{ route('afiliados') }}"
                                       class="nav-link {{ request()->is('lista-afiliados*') ? 'active' : '' }}"
                                       id="wpp-msgs">
                                        <i class="fas fa-people-arrows"></i>
                                        <p>Afiliados</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('painel.solicitacaoAfiliados') }}"
                                       class="nav-link {{ request()->is('solicitacao-pagamento*') ? 'active' : '' }}"
                                       id="wpp-msgs">
                                        <i class="fas fa-dollar-sign"></i>
                                        <p>Solicitação de Pgto</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content -->

    <div id="sidebar-overlay"></div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark" style="background: #010140 !important">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<script src="{{ cdnAsset('/js/admin-bundle.min.js') }}"></script>

@stack('scripts')
<script>
    $(document).ready(function (){
        initAjaxSetup();
        setUrlsPages();

    })
</script>
@stack('datetimepicker')

</body>

</html>
