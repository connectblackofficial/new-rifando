<!-- Stored in resources/views/layouts/master.blade.php -->
<?php

$configSite = getSiteConfig();
?>
<html style="height: auto;">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <link href="{{ cdnAsset('css/admin.css') }}" rel="stylesheet">
    <title><?php echo $configSite->name; ?> @if(isset($pgTitle))
            - {{$pgTitle}}
        @endif</title>
    <script>
        var ROUTES = <?= getJsRoutes() ?>;
        var CDN_URL = "<?= cdnAsset() ?>";
    </script>
    @section('scripts-top')

    @endsection
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed" style="height: auto; position: relative">

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
                    @if(Auth::user()->isSuperAdmin())
                        @include("layouts.menus.super-admin-menu")
                    @else
                        @include("layouts.menus.admin-menu")
                    @endif


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

@include("layouts.modal-url")
<script src="{{ cdnAsset('/js/admin-bundle.min.js') }}"></script>

@stack('scripts')
<script>
    $(document).ready(function () {
        initPage();
    })
</script>
@yield('scripts-footer')
@stack('datetimepicker')

</body>

</html>
