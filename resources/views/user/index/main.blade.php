@extends('user.master')

@section('title', 'Главная страница')

@section('content')

    <div id="indexVue">
        <div class="col s12 m12 l12 index-with_anime--background">
            <div class="col s12 m12 l12 auth-register">
                @if ( Auth::check() )
                    <span style="color: #fff">Вы уже авторизованы. У Вас 0 новых уведомлений.</span>
                @else
                    <a class="waves-effect waves-light btn modal-auth_a"
                       href="#auth-modal">Авторизация</a>
                    <a href="/register"
                       class="waves-effect waves-light btn">Регистрация</a>
                @endif
            </div>
            <div class="row col s12 m12 l12 background-description">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
            <div class="row col s12 m12 l12 background-slider">
                <div class="row col s12 m12 l4">
                    <div class="title">
                        <h5 class="center-align">Последние юзеры</h5>
                    </div>
                    <ul class="collection">
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-user-circle"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-user-circle"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-user-circle"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-user-circle"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="row col s12 m12 l4">
                    <div class="title">
                        <h5 class="center-align">Новые темы</h5>
                    </div>
                    <ul class="collection">
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-comment"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-comment"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-comment"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-comment"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="row col s12 m12 l4">
                    <div class="title">
                        <h5 class="center-align">Последние видео</h5>
                    </div>
                    <ul class="collection">
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-play-circle"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg" alt="" class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-play-circle"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg" alt="" class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-play-circle"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="collection-item avatar">
                            <img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg"
                                 alt=""
                                 class="circle">
                            <span class="title">Title</span>
                            <a href="#!"
                               class="secondary-content">
                                <i class="fa fa-play-circle"
                                   aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row main-container">

            <div class="col s12 m12 l7 main-content">
                <div class="row col s12 m12 l12 each-anime">
                    <h5 class="center-align">Название аниме</h5>
                    <div class="col s12 m12 l12 each-anime_image">
                        <img src="https://i.ytimg.com/vi/WDElrHcEEGY/maxresdefault.jpg">
                    </div>
                    <p class="each-anime_description">
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                    </p>
                    <div class="col s12 m12 l12 each-anime_bottom--panel">
					<span class="each-anime_bottom--panel__author">
						<img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg">
					</span>
                        <span class="each-anime_bottom--panel__events">
						<i class="fa fa-eye left"
                           aria-hidden="true"></i>
						<span>2</span>
					</span>
                        <span class="each-anime_bottom--panel__likes">
						<i class="fa fa-heart-o left"
                           aria-hidden="true"></i>
						<span>3</span>
					</span>
                        <a href="#">
                            <i class="fa fa-arrow-circle-right right"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="row col s12 m12 l12 each-anime">
                    <h5 class="center-align">Название аниме</h5>
                    <div class="col s12 m12 l12 each-anime_image">
                        <img src="https://i.ytimg.com/vi/WDElrHcEEGY/maxresdefault.jpg">
                    </div>
                    <p class="each-anime_description">
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                    </p>
                    <div class="col s12 m12 l12 each-anime_bottom--panel">
					<span class="each-anime_bottom--panel__author">
						<img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg">
					</span>
                        <span class="each-anime_bottom--panel__events">
						<i class="fa fa-eye left"
                           aria-hidden="true"></i>
						<span>2</span>
					</span>
                        <span class="each-anime_bottom--panel__likes">
						<i class="fa fa-heart-o left"
                           aria-hidden="true"></i>
						<span>3</span>
					</span>
                        <a href="#">
                            <i class="fa fa-arrow-circle-right right"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="row col s12 m12 l12 each-anime">
                    <h5 class="center-align">Название аниме</h5>
                    <div class="col s12 m12 l12 each-anime_image">
                        <img src="https://i.ytimg.com/vi/WDElrHcEEGY/maxresdefault.jpg">
                    </div>
                    <p class="each-anime_description">
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                        Что-то будет на русскомЧто-то будет на русскомЧто-то будет на русскомЧто-то будет на русском
                    </p>
                    <div class="col s12 m12 l12 each-anime_bottom--panel">
					<span class="each-anime_bottom--panel__author">
						<img src="https://pp.vk.me/c626319/v626319889/2f8c2/mNoZa2C3VIY.jpg">
					</span>
                        <span class="each-anime_bottom--panel__events">
						<i class="fa fa-eye left"
                           aria-hidden="true"></i>
						<span>2</span>
					</span>
                        <span class="each-anime_bottom--panel__likes">
						<i class="fa fa-heart-o left"
                           aria-hidden="true"></i>
						<span>3</span>
					</span>
                        <a href="#">
                            <i class="fa fa-arrow-circle-right right"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col s12 m12 l3 right-sidebar">
                <div class="col s12 m12 l12 right-block">
                    <h5 class="col s12 m12 12 title center-align">Сортировка</h5>
                    <div class="content-block">
                        <p>
                            <input name="group1"
                                   type="radio"
                                   id="by_new">
                            <label for="by_new">Новые</label>
                        </p>
                        <p>
                            <input name="group1"
                                   type="radio"
                                   id="by_old">
                            <label for="by_old">Старые</label>
                        </p>
                    </div>
                </div>
                <div class="col s12 m12 l12 right-block">
                    <h5 class="col s12 m12 12 title center-align">Популярные</h5>
                    <div class="content-block">
                        <div class="col s12 m12 l12 best-anime_each">
                            <img src="http://www.wallpaperscharlie.com/wp-content/uploads/2016/10/HD-Anime-Wallpapers-4.jpg">
                            <a href="#">Название аниме</a>
                        </div>
                        <div class="col s12 m12 l12 best-anime_each">
                            <img src="http://www.wallpaperscharlie.com/wp-content/uploads/2016/10/HD-Anime-Wallpapers-4.jpg">
                            <a href="#">Название аниме</a>
                        </div>
                        <div class="col s12 m12 l12 best-anime_each">
                            <img src="http://www.wallpaperscharlie.com/wp-content/uploads/2016/10/HD-Anime-Wallpapers-4.jpg">
                            <a href="#">Название аниме</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('user.auth.auth')

@endsection