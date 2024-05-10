@extends('layout.mainSkeleton')

@section('title')
    <title>Pokemon</title>
@endsection

@section('section-css')
    <style>
        #spinner,
        #check-icon,
        #ban-icon {
            display: none;
        }
    </style>
@endsection

@section('section-js')
    <script>
        function clickStart() {
            $('#text').hide();
            $('#spinner').show();

            $('#check-icon').hide();
            $('#ban-icon').hide();

            //Comienzo ajax
            $.ajax({
                    url: "{{ route('pokemon.load') }}",
                    method: "GET"
                })
                .done(function(data, status, jqXHR) {
                    console.log(data);
                    console.log(status);
                    console.log(jqXHR);

                    $('#spinner').hide();

                    if (data.status == true) {
                        $('#check-icon').show();
                    } else {
                        $('#ban-icon').show();
                    }
                    setTimeout(function() {
                        window.location.href = "{{ route('pokemon.list', ['page' => 1]) }}";
                    }, 1000);



                })
                .fail(function(jqXHR, status, error) {
                    console.log(jqXHR);
                    console.log(status);
                    console.log(error);
                    $('#spinner').hide();
                    $('#ban-icon').show();
                }).always(function() {
                    setTimeout(function() {
                        $('#check-icon').hide();
                        $('#ban-icon').hide();
                        $('#startContainer').hide();
                    }, 1000);
                });
        }
    </script>
@endsection

@section('main')
    <div class="col-10 d-flex justify-content-center align-items-center flex-column">
        <div id="startContainer">
            <h1>Pokemons</h1>
            <div class="btn btn-dark d-flex justify-content-center" onclick="clickStart()">
                <span id="text" style="text-decoration: none; color: white;">Iniciar</span>
                <div class="spinner-border" id="spinner"></div>
                <i id="check-icon" class="bi bi-check"></i>
                <i id="ban-icon" class="bi bi-slash-circle"></i>
            </div>
        </div>
    </div>
@endsection
