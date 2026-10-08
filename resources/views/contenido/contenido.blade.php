@extends('principal')

@section('contenido')

    <template v-if="menu == 0">
        <principal-component></principal-component>
    </template>


    @php

        use App\Http\Controllers\AdmSessionController;

       

        $var2 = AdmSessionController::listarVentanas();

    @endphp


    @foreach($var2 as $ventana)

        <template v-if="menu == {{ $ventana->codventana }}">

            <{{ $ventana->template }}
                :codventana="{{ $ventana->id }}"
                :idmodulo="{{ $ventana->idmodulo }}"
                @cambiar-ventana="menu = $event"
            >
            </{{ $ventana->template }}>

        </template>

    @endforeach

@endsection


@section('bodyheader')

    @php

        use App\Http\Controllers\AdmUserController;

        $user = AdmUserController::getUser();

        $idsucursal = session('idsuc');
        $nomsucursal = session('nomsucursal');
        $nomrol = session('nomrol');

    @endphp

    <Body_header
        user="{{ $user->name }}"
        nomsucursal="{{ $nomsucursal }}"
        nomrol="{{ $nomrol }}">
    </Body_header>

@endsection