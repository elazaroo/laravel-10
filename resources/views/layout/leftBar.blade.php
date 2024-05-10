<style>
    @import url('https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap');

    #leftBar {
        font-family: "Comfortaa", sans-serif;
    }

    #respeque {
        display: none;
    }

    @media (max-width: 767px) {
        #resgrande {
            display: none;
        }

        #respeque {
            display: flex;
            margin-block: 1.5rem;
            margin-left: 0rem;
        }

        .colored {
            color: antiquewhite !important;
        }
    }
</style>

<script>
    var selectedElement = null;

    function addColor(element) {
        if (selectedElement) {
            selectedElement.classList.remove('colored');
        }
        element.classList.add('colored');
        selectedElement = element;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(e) {
            var submenu1 = document.getElementById('submenu1');
            if (!e.target.closest('#submenu1') && !e.target.closest('[href="#submenu1"]')) {
                submenu1.classList.remove('show');
            }
        });
    });
</script>

{{--  class="container-fluid bg-dark" id="leftBar"> --}}
<div class="col-2 bg-dark" id="leftBar">
    <div class="row flex-nowrap">
        <div
            class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100 border-white">
            <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span style="font-size: calc(1vh + 1vw);">Menu</span>
            </a>
            <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                <li class="nav-item">
                    <a href="/" class="nav-link align-middle px-0">
                        <span class="ms-1 text-white" style="font-size: calc(0.8vh + 0.8vw);"
                            id="resgrande">Inicio</span>
                        <i class="fa-solid fa-house" id="respeque" style="color: white;" onclick="addColor(this)"></i>
                    </a>
                </li>
                <li>
                    <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                        <span class="ms-1 text-white"
                            style="font-size: calc(0.8vh + 0.8vw);"id="resgrande">Publicaciones</span>
                        <i class="fa-regular fa-share-from-square" id="respeque" style="color: white;"
                            onclick="addColor(this)"></i>
                    </a>
                    <ul class="collapse nav flex-column" id="submenu1" data-bs-parent="#menu"
                        style="margin-left: 2rem">
                        <li class="w-100">
                            <a href="/crear-publicacion" class="nav-link px-0">
                                <span class=" text-white" style="font-size: calc(0.55vh + 0.55vw);"id="resgrande">Crear
                                    publicación</span>
                                <i class="fa-solid fa-plus" id="respeque" style="color: white;"
                                    onclick="addColor(this)"></i>
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="/publicaciones" class="nav-link px-0">
                                <span class="text-white" style="font-size: calc(0.55vh + 0.55vw);"id="resgrande">Todas
                                    las publicaciones</span>
                                <i class="fa-solid fa-border-all" id="respeque" style="color: white;"
                                    onclick="addColor(this)"></i>
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="{{ route('post.index', ['filtro' => 'mis-publicaciones']) }}"
                                class="nav-link px-0">
                                <span class=" text-white" style="font-size: calc(0.55vh + 0.55vw);"id="resgrande">Mis
                                    publicaciones</span>
                                <i class="fa-regular fa-user" id="respeque" style="color: white;"
                                    onclick="addColor(this)"></i>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/cuenta" class="nav-link px-0 align-middle">
                        @if (Auth::check() == null)
                            <span class="ms-1 text-white" style="font-size: calc(0.8vh + 0.8vw);"id="resgrande">Inicia
                                sesión</span>
                        @else
                            <span class="ms-1 text-white" style="font-size: calc(0.8vh + 0.8vw);"id="resgrande">Mi
                                cuenta</span>
                        @endif
                        <i class="fa-solid fa-user" id="respeque" style="color: white;" onclick="addColor(this)"></i>
                    </a>
                    <a href="/logout" class="nav-link px-0 align-middle">
                        @auth
                            <span class="ms-1 text-white" style="font-size: calc(0.8vh + 0.8vw);"id="resgrande">Cerrar
                                sesión</span>
                            <i class="fa-solid fa-arrow-right-from-bracket" id="respeque" style="color: white;"
                                onclick="addColor(this)"></i>
                        @endauth
                    </a>
                    @auth
                        <a href="{{ route('pokemon.index') }}" class="nav-link px-0 align-middle">
                            <span class="ms-1 text-white"
                                style="font-size: calc(0.8vh + 0.8vw);"id="resgrande">Pokemon</span>
                            <i class="fa-solid fa-gamepad" id="respeque" style="color: white;"
                                onclick="addColor(this)"></i>
                        </a>
                    @endauth
                </li>
            </ul>
            <hr>
            <div class="block">
                @include('layout.footer')
            </div>
        </div>
    </div>
</div>
