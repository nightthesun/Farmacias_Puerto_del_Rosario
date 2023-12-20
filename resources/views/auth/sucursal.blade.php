@extends('layouts.app')

@section('title','Sucursal')

@section('content')
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
              <h3 class="mb-5">Seleccionar Sucursal</h3>
              <form action="" class="mt-4" method="POST">
                @csrf
                <select name="sucur" id="" class="form-select">Seleccionar Sucursal
                    @foreach ($sucursales as $item)
                        <option value="{{ $item->id }}">{{ $item->nomsuc }}  - {{ $item->nomrole }}  </option>
                    @endforeach
                </select>
                <hr>
                <button type="submit" class="btn btn-primary btn-block mb-4">Seleccionar</button>
              </form> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
{{-- <div class="form-control">
    <h1 class="form-contrl">Inicio de Session</h1>
    
    <form action="" class="mt-4" method="POST">
        @csrf
        <select name="sucur" id="" class="form-control">Seleccionar Sucursal
            @foreach ($sucursales as $item)
                <option value="{{ $item->id }}">{{ $item->nomsuc }}  - {{ $item->nomrole }}  </option>
            @endforeach
            
        </select>
        
        <button type="submit" class="btn btn-primary btn-block mb-4">Seleccionar</button>
    </form> 
</div> --}}
    
    

    
@endsection