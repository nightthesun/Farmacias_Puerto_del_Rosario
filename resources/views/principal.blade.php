<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema De Control Farmacia Puerto del Rosario">
    <meta name="author" content="Eddy claros">
    <meta name="keyword" content="Sistema De Control Farmacia Puerto del Rosario">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Sistema Farmacia - Puerto de Rosario</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icons -->
    <link href="css/plantilla.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
    
    
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
       <!--  <img src="storage/producto/Ww84GOcIMegTRhMCFV5D1AFnCBTzUW7H.jpg" alt=""> -->
        <header class="app-header navbar">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#"></a>{{-- <font class="nav-link">Puerto del Rosario</font> --}} 
            
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="nav navbar-nav d-md-down-none">
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Escritorio</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Configuraciones</a>
                </li>
            </ul>
            @yield('bodyheader')
        </header>

        <div class="app-body">
            
            @include('plantilla.sidebar')
            <!-- Contenido Principal -->
            @yield('contenido')
            
            <!-- /Fin del contenido principal -->
        </div>

    </div>

    <footer class="app-footer" style="text-align:center;">
        <div class="container-fluid">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <span><!--a href="http://www.puertodelrosario.com/"-->Puerto del Rosario</a> &copy; {{date("Y")}}</span>
                    <br>
                    {{-- <span>La Paz - Bolivia</span> --}}
                </div>
                <div class="col"></div>
            </div>
        </div>
        {{-- <span class="ml-auto">Desarrollado por <a href="https://eddyclaros.github.io">EddyClaros</a></span> --}}
    </footer>

    
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
    
    
</body>

</html>