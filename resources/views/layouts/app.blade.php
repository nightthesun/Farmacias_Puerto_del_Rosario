<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" > --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <title>@yield('title')</title>
</head>
<body>

    <nav class="navbar navbar-light" style="background-color: #e3f2fd;">
        <div class="container-fluid">
          <span class="navbar-brand mb-0 h1">Puerto del Rosario</span>

          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @if (auth()->check())
                    <p class="text-xl">Bienvenido <p>{{ auth()->user()->name }}</p></p>
                    <li class="nav-item">
                        <a href="{{ route('login.destroy') }}" class="nav-link active">Cerrar Sesion</a>
                    </li>
                @else 
                <li class="nav-item">
                    <a href="{{ route('login.index') }}" class="nav-link">Login</a>
                </li>
                <li>
                    {{-- <a href="{{ route('registro.index') }}" class="font-semibold border-2 border-white py-2 px-4 rounded-md hover:bg-white hover:text-indigo-700">Registro</a> --}}
                </li>
                @endif
            </ul>
          </div>

         
        </div>
      </nav>
  
    @yield('content')
    @yield('recuperarpass')
    @yield('codigo')
    
</body>
</html>