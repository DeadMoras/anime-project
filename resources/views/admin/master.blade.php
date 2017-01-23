<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('admin._patterns._header_link')
</head>
<body>
<meta id="token" name="token" value="{{csrf_token()}}">

<div class="row">

    <div class="col s12 m4 l3 left-bar">
        <navbar class="col s12 m12 l12">
            @include('admin._patterns._left_bar')
        </navbar>
    </div>

    <div class="col s12 m8 l9 main-container">
        @yield('content')
    </div>

</div>

@include('admin._patterns._footer_links')
@yield('other_footer_links')
</body>
</html>