<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta id="token" name="token" value="{{csrf_token()}}">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bower_components/materialize/dist/css/materialize.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
</head>
<body>
    <div id="app">
        <header class="col s12 m12 l12">
            <header-component></header-component>
        </header>

        <router-view></router-view>

        @if ( Request::url() !== 'http://anime-music.ru/register' )
            <footer class="page-footer">
                <footer-component></footer-component>
            </footer>
        @endif
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/materialize/dist/js/materialize.js"></script>
    <script src="{{ asset('js/for-materialize.js') }}"></script>
</body>
</html>