<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Agora') }}</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    {{--  --}}
    <script src="{{asset('plugin/fontawesome5/svg-with-js/js/fontawesome-all.js')}}"></script>
</head>
<body>
    <div id="app"> 
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
</body>
</html>
