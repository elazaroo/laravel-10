@extends('layout.mainSkeleton')

@section('title')
    <title>Lista de pokemons</title>
@endsection

@section('section-css')
    <style>

    </style>
@endsection

@section('section-js')
    <script></script>
@endsection

@section('main')
    <div class="col-10 d-flex justify-content-center align-items-center flex-column">
        <h1>Lista de pokemons</h1>
        <div class="row justify-content-center">
            @foreach ($pokemonData as $pokemon)
                <div class="card" style="width: 16rem;">
                    <img src="{{ $pokemon['image'] }}" class="card-img-top" alt="{{ $pokemon['name'] }}">
                    <div class="card-body">
                        <a href="{{ route('pokemon.show', $pokemon['id']) }}" class="text-decoration-none">
                            <h5 class="card-title text-black text-uppercase">{{ $pokemon['name'] }}</h5>
                        </a>
                        @foreach ($pokemon['types'] ?? [] as $type)
                            <p class="card-text">Tipo: <span class="text-uppercase">{{ $type }}</span></p>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-2">
            @isset($pokemon['types'])
                <a href="{{ route('pokemon.list', ['page' => $page - 1]) }}" class="btn btn-primary btn-dark">Anterior</a>
                <a href="{{ route('pokemon.list', ['page' => $page + 1]) }}" class="btn btn-primary btn-dark">Siguiente</a>
            @endisset
            @isset($selectedType)
                <a href="{{ route('pokemon.type', ['page' => $page - 1, 'type' => $selectedType]) }}"
                    class="btn btn-primary btn-dark">Anterior</a>
                <a href="{{ route('pokemon.type', ['page' => $page + 1, 'type' => $selectedType]) }}"
                    class="btn btn-primary btn-dark">Siguiente</a>
            @endisset
            <select class="btn btn-primary btn-dark" id="select" onchange="window.location.href=this.value">
                <option value="{{ route('pokemon.list', ['page' => 1]) }}" class="text-uppercase btn btn-primary btn-dark">
                    TODOS</option>
                @foreach ($typesList as $type)
                    <option value="{{ route('pokemon.type', ['type' => $type, 'page' => 1]) }}"
                        class="text-uppercase btn btn-primary btn-dark"
                        {{ isset($selectedType) && $selectedType == $type ? 'selected' : '' }}>
                        {{ $type }}</option>
                @endforeach
            </select>
        </div>
    @endsection
