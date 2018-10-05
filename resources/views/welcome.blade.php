<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="display-4 text-center">Kies welke text je het beste kan lezen.</h1>
                </div>
            </div>

            <div class="row">
                <div class="col mr-3" style="background-color: rgb({{ "$r, $g, $b" }}); color: black;" onclick="
                        event.preventDefault();
                        document.getElementById('form-black').submit();">
                    <h1 class="display-1">Text</h1>
                </div>
                <div class="col mr-3" style="background-color: rgb({{ "$r, $g, $b" }}); color: white;" onclick="
                        event.preventDefault();
                        document.getElementById('form-white').submit();">
                    <h1 class="display-1">Text</h1>
                </div>
            </div>

            <div class="row">
                <div class="col mr-3" style="background-color: rgb({{ "$r, $g, $b" }}); color: {{ $color }};">
                    <h1 class="display-1">Text</h1>
                </div>
            </div>
        </div>

        <form id="form-black" method="POST" action="{{ route('submit') }}">
            @csrf
            <input type="hidden" name="color" value="black">
            <input type="hidden" name="r" value="{{ $r }}">
            <input type="hidden" name="g" value="{{ $g }}">
            <input type="hidden" name="b" value="{{ $b }}">
        </form>

        <form id="form-white" method="POST" action="{{ route('submit') }}">
            @csrf
            <input type="hidden" name="color" value="white">
            <input type="hidden" name="r" value="{{ $r }}">
            <input type="hidden" name="g" value="{{ $g }}">
            <input type="hidden" name="b" value="{{ $b }}">
        </form>
        <a href="{{ route('colors') }}">Colors</a>
    </body>
</html>
