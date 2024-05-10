@extends('layout.mainSkeleton')

@section('title')
    <title>Publicaciones</title>
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
        <h3 class="ms-2 mt-3 mb-5">{{ request('filtro') === 'mis-publicaciones' ? 'Mis publicaciones' : 'Todas las publicaciones' }}</h3>
        @forelse ($posts as $post)
            <div class="card m-2">
                <div class=" border-1 d-flex justify-content-between" style="background-color: #c0c7ce94;">
                    <span class="px-3">{{ $post->user->name }}</span>
                    <span class="px-3">01/01/2024</span>
                </div>
                <div class="card-header">
                    <h5>{{ $post->title }}</h5>
                </div>
                <div class="card-body">
                    <p>{{ $post->content }}</p>
                </div>
                <div class="card-footer">
                    <div class="d-none d-lg-flex" id="botones">
                        @auth
                            @can ('update', $post)
                                <a href="{{ route('post.edit', $post->id) }}" class="btn btn-dark">Editar</a>
                            @endcan
                            @can('delete', $post)
                                <form class="ms-3" method="POST" action="{{ route('post.delete', $post->id) }}">
                                    @csrf
                                    @method('delete')
                                    <a href="#" class="btn btn-dark" onclick="event.preventDefault(); this.closest('form').submit();">Eliminar</a>
                                </form>
                            @endcan
                        @endauth
                    </div>
                    <div class="d-flex d-lg-none" id="botonesResponsive">
                        @auth
                            @can ('update', $post)
                                <a href="{{ route('post.edit', $post->id) }}" class="btn btn-dark"><i class="fa-solid fa-pencil"></i></a>
                            @endcan
                            @can('delete', $post)
                                <form class="ms-3" method="POST" action="{{ route('post.delete', $post->id) }}">
                                    @csrf
                                    @method('delete')
                                    <a href="#" class="btn btn-dark" onclick="event.preventDefault(); this.closest('form').submit();"><i class="fa-solid fa-trash"></i></a>
                                </form>
                            @endcan
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                No hay publicaciones
            </div>
        @endforelse
    </div>
@endsection
