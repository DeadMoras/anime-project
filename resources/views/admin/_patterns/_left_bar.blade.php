<ul>
    <li><a href="/admin">Main page</a></li>
    <li><a href="{{ action('Admin\UsersController@index') }}">Users</a></li>
    <li><a href="{{ action('Admin\AnimeController@index') }}">Anime</a></li>
    <li><a href="#">Manga</a></li>
    <li><a href="#">Forum</a></li>
    <li><a href="{{ vkUrl() }}">Vk token</a></li>
</ul>