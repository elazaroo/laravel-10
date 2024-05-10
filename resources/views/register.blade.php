@extends('layout.mainSkeleton')

@section('title')
    <title>Registro</title>
@endsection

@section('section-css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap');

    #contenedora{
        font-family: "Comfortaa", sans-serif;
    }
</style>

@endsection

@section('section-js')

@endsection

@section('main')
    <div class="col-10 bord" id="contenedora">
        <div id="errorMsg">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <h1 class="mt-3 ms-3">Registro</h1>
        <form class="ms-3" action="{{ route('validar-registro') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-none d-lg-flex align-items-center" id="opciones">
                <button type="submit" class="btn btn-primary" style="margin-right: 20px;">Registrarse</button>
                <p class="mb-0" style="line-height: 1.5;">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar sesión</a></p>
            </div>
            </div>
            <div class="d-block d-lg-none" id="opcionesResponsive">
                <button type="submit" class="btn btn-primary" style="margin-right: 20px;">Registrarse</button>
                <p class="mt-2" style="line-height: 1.5;">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar sesión</a></p>
            </div>
        </form>
    </div>
@endsection
