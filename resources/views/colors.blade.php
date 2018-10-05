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
        <a href="{{ route('home') }}">Home</a>
        <div class="container">
            <div class="row">
                @foreach($colors as $color)
                <div class="col mr-3" style="background-color: rgb({{ "{$color['r']}, {$color['g']}, {$color['b']}" }}); color: {{ $color['color'] }};">
                    <h1 class="display-1">Text</h1>
                </div>
                @endforeach
            </div>
        </div>
    </body>
</html>
