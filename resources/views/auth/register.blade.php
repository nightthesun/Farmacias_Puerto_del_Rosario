@extends('layouts.app')

@section('title','Register')

@section('content')
    <h1 class="text-5xl text-center pt-24">Registro de Usuario</h1>
    <div class="bloc mx-auto my-12 p-8 bg-white w-1/3 border border-gray-200 rounded-lg shadow-lg">
        <h1 class="text-3xl text-center font-bold">Registro</h1>
        <form action="" class="mt-4" method="POST">
            @csrf
            <input type="text" class="border border-gray-200 rounded-md bg-gray-200 w-full text-lg placeholder-gray-900 p-2 my-2 focus:gb-white" placeholder="Nombre..." id="name" name="name">
            @error('name')
            <p class="border border-red-500 rounded-md bg-red-100 w-full text-red-600 p-2 my-2">* {{ $message }}</p>    
            @enderror

            <input type="email" class="border border-gray-200 rounded-md bg-gray-200 w-full text-lg placeholder-gray-900 p-2 my-2 focus:gb-white" placeholder="Email..." id="email" name="email">
            @error('email')
            <p class="border border-red-500 rounded-md bg-red-100 w-full text-red-600 p-2 my-2">* {{ $message }}</p>    
            @enderror

            <input type="password" class="border border-gray-200 rounded-md bg-gray-200 w-full text-lg placeholder-gray-900 p-2 my-2 focus:gb-white" placeholder="Password..." id="password" name="password">
            @error('password')
            <p class="border border-red-500 rounded-md bg-red-100 w-full text-red-600 p-2 my-2">* {{ $message }}</p>    
            @enderror
            <input type="password" class="border border-gray-200 rounded-md bg-gray-200 w-full text-lg placeholder-gray-900 p-2 my-2 focus:gb-white" placeholder="Password Confirmacion" id="password_confirmation" name="password_confirmation">
            
            <button type="submit" class="rounded-md bg-indigo-500 w-full text-lg text-white font-semibold p-2 my-3 hover:bg-indigo-600">Registrar</button>
        </form>
    </div>

    
@endsection