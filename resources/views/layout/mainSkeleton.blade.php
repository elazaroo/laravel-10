<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/bootstrap/bootstrap.min.css">
    <script src="/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/0d040b51d2.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css" rel="stylesheet">
    @yield('title')
    <style>

    </style>
    @yield('section-css')
    @yield('section-js')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            @include('layout.leftBar')
            @yield('main')
        </div>
    </div>
</body>

</html>
