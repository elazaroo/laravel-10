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
    <div class="col-10 justify-content-center d-flex">
        <div class="card" style="width: 80%;">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title text-black text-uppercase">{{ $pokemonData['name'] }}</h5>
                <div class="d-flex">
                    @foreach ($pokemonData['types'] as $type)
                        <p class="card-text text-uppercase p-1 mx-1">
                            {{ $type }}</p>
                    @endforeach
                </div>
            </div>
            <div class="card-body">
                <img class="card-img-top" src="{{ $pokemonData['image'] }}" alt="{{ $pokemonData['name'] }}"
                    style="width: 10rem; height: auto;">
            </div>
            <div class="d-flex w-100 ">
                <div class="w-50">
                    <h5 class="text-center">Involuciones</h5>
                    <div class="d-flex justify-content-center flex-wrap">
                        @forelse ($invoData as $data)
                            <div class="card m-2" style="width: 10rem;">
                                <img class="card-img-top" src="{{ $data['image'] }}" alt="{{ $data['name'] }}"
                                    style="width: 10rem; height: auto;">
                                <div class="card-body">
                                    <a href="{{ route('pokemon.show', $data['id']) }}" class="text-decoration-none">
                                        <h5 class="card-title text-center text-black">
                                            {{ $data['name'] }}
                                        </h5>
                                    </a>
                                    <div class="d-flex justify-content-center">
                                        @foreach ($data['types'] as $type)
                                            <p class="card-text text-uppercase p-1 mx-1">
                                                {{ $type }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>No tiene involuciones</p>
                        @endforelse
                    </div>
                </div>
                <div class="w-50">
                    <h5 class="text-center">Evoluciones</h5>
                    <div class="d-flex justify-content-center flex-wrap">
                        @forelse ($evoData as $data)
                            <div class="card m-2" style="width: 10rem;">
                                <img class="card-img-top" src="{{ $data['image'] }}" alt="{{ $data['name'] }}"
                                    style="width: 10rem; height: auto;">
                                <div class="card-body">
                                    <a href="{{ route('pokemon.show', $data['id']) }}" class="text-decoration-none">
                                        <h5 class="card-title text-center text-black">
                                            {{ $data['name'] }}
                                        </h5>
                                    </a>
                                    <div class="d-flex justify-content-center">
                                        @foreach ($data['types'] as $type)
                                            <p class="card-text text-uppercase p-1 mx-1">
                                                {{ $type }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>No tiene evoluciones</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
