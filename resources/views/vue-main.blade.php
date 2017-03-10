<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta id="token" name="token" value="{{csrf_token()}}">
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('/bower_components/materialize/dist/css/materialize.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/font-awesome.min.css') }}">
</head>
<body>
    <div id="app">
        <div id="success-js_button"></div>
        <header class="col s12 m12 l12">
            <header-component></header-component>
        </header>

        <router-view></router-view>

        <footer class="page-footer">
            <footer-component></footer-component>
        </footer>
    </div>

    <script src="//cdn.jsdelivr.net/alertifyjs/1.9.0/alertify.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('/bower_components/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('/bower_components/materialize/dist/js/materialize.js') }}"></script>
    <script src="{{ asset('js/for-materialize.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js"></script>
</body>
</html>