@extends('layouts.app')

@section('recuperarpass')

<style>
    .background-radial-gradient {
      background-color: hsl(218, 41%, 15%);
      background-image: radial-gradient(650px circle at 0% 0%,
          hsl(218, 41%, 35%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%),
        radial-gradient(1250px circle at 100% 100%,
          hsl(218, 41%, 45%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%);
    }
    body{
        background-color: #E3F2FD;
      }
</style>
<section class="background-radial-gradient overflow-hidden" style="background-color: #20B2AA;">
    <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card shadow-2-strong" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">
  
              <h3 class="mb-5">Generar Nuevo Password</h3>
              <form action="" class="mt-4" method="POST">
                @csrf
                <div class="alert alert-success bg-soft-primary border-0" role="alert">
                    Ingrese su direccion de correo electronico y le enviaremos un email con un codigo para confirmar su identidad.
                </div>                    
                @if(isset($error))
                  @if($error=='error')
                    <h4 style="color: crimson">No existe el Email <br> <small>Intentelo otra vez</small></h4>
                  @endif
                @endif
                    
                <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="Direccion de Correo" required>
                </div>
            <br>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-lg btn-block">Enviar Codigo</button>
                </div>
                <br>
                <div class="form-group other_auth_links">
                    <a class="" href="{{ route('login.index') }}"><-Volver</a>
                    {{-- <a class="" href="https://procraft.studio">Register</a> --}}
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection