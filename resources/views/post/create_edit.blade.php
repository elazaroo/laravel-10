@extends('layout.mainSkeleton')

@section('title')
    <title>{{ empty($post) ? 'Nueva publicación' : 'Actualizar publicación' }}</title>
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
    <div class="col-10" id="contenedora">
        <h2 class="mt-3 ms-2">{{ empty($post) ? 'Crear publicación' : 'Editar publicación' }}</h2>
        <form method="POST" action="{{ empty($post) ? route('post.store') : route('post.update', $post) }}" class="card m-2 mt-3">
            @csrf

            @if(empty($post))
                @method('post')
            @else
                @method('put')
            @endif
            <div class="card-header" id="fondo">
                <input type="text" name="title" class="form-control" value="{{ old('title', empty($post) ? '' : $post->title) }}" placeholder="Titulo" required>
            </div>
            <div class="card-body">
                <textarea name="content" class="form-control" required>{{ old('content', empty($post) ? 'Mi Descripción...' : $post->content) }}</textarea>
            </div>
            <div class="card-footer" id="fondo">
                <div class="d-none d-lg-block" id="botones">
                    <button class="btn btn-dark">{{ empty($post) ? 'Publicar' : 'Actualizar' }}</button>
                    <a href="/publicaciones" class="btn btn-dark">Cancelar</a>
                </div>
                <div class="d-block d-lg-none" id="botonesResponsive">
                    <button type="submit" class="btn btn-dark"><i class="fa-solid fa-paper-plane"></i></button>
                    <a href="/publicaciones" class="btn btn-dark"><i class="fa-solid fa-times"></i></a>
                </div>
            </div>
        </form>
    </div>
@endsection
