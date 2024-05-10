@extends('layout.mainSkeleton')

@section('title')
    <title>Mi cuenta</title>
@endsection

@section('section-css')
<style>

</style>

@endsection

@section('section-js')

@endsection

@section('main')
    <div class="col-10">
        <h1 class="mt-3 ms-3">
            Mi cuenta
            @auth
                {{ Auth::user()->name }}
            @endauth
        </h1>
        <p class="ms-3">En esta sección puedes ver tus datos personales</p>
        <p class="ms-3">También puedes editar tus datos personales</p>
        <div class="d-flex justify-content-center">
            <table class="table table-bordered w-25 table-striped ">
                <tr>
                    <td>Nombre:</td>
                    <td>{{ Auth::user()->name }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ Auth::user()->email }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
