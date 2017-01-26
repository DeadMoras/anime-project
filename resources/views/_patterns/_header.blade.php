<div class="main-header">
    <ul id="nav-mobile"
        class="right hide-on-med-and-down">
        <li><a href="#">Ссылка</a></li>
        <li><a href="#">Ссылка</a></li>
        <li><a href="#">Ссылка</a></li>
        <li><a href="#">Ссылка</a></li>
        <li><form action="/logout"
                  method="post">
                {{ csrf_field() }}
                <button class="header-button_logout">Выход</button>
            </form></li>
    </ul>
</div>
<ul id="dropdown1" class="dropdown-content header-menu">
    <li><a href="#!">one</a></li>
    <li><a href="#!">two</a></li>
    <li class="divider"></li>
    <li><a href="#!">three</a></li>
</ul>
@if ( Auth::check() )
    <a class="dropdown-button" href="#!" data-activates="dropdown1">
        <div class="col s3 m3 l4 right-header_profile">
            <img src="{{ asset('images/user/'.userInfo('getAvatar')) }}">
            <span>#{{ userInfo('getLogin') }}</span>
        </div>
    </a>
@endif