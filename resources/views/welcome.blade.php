@extends('layout.mainSkeleton')

@section('title')
    <title>Incio</title>
@endsection

@section('section-css')
    <style>

    </style>
@endsection

@section('section-js')
@endsection

@section('main')
    <div class="col-10">
        <div id="errorMsg">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div id="msg">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <h1 class="mt-3 ms-3">
            Bienvenido
            @auth
                {{ Auth::user()->name }}
            @endauth
        </h1>
        <p class="ms-3">Este es un proyecto de prueba para el curso de Laravel</p>
        <p class="ms-3">Puedes ver las publicaciones <a href="/publicaciones">aqui</a></p>
        <p class="ms-3">O crear una nueva publicacion <a href="/crear-publicacion">aqui</a></p>
        @if (Auth::check() == null)
            <p class="ms-3">Inicia sesión <a href="/login">aquí</a></p>
            <p class="ms-3">O registrate <a href="/register">aquí</a></p>
        @else
            <p class="ms-3">¡Genial! Tienes la sesión iniciada</p>
        @endif
    </div>
@endsection
