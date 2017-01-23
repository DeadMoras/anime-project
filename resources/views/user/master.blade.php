<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('_patterns._header_link')
</head>
<body>
<header class="col s12 m12 l12">
    @include('_patterns._header')
</header>

<meta id="token" name="token" value="{{csrf_token()}}">

@yield('content')


@if ( Request::url() !== 'http://anime-music.ru/register' )
    <footer class="page-footer">
        @include('_patterns._footer')
    </footer>
@endif

@include('_patterns._footer_links')
@yield('other_footer_links')
</body>
</html>