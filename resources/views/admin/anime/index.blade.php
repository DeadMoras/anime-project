@extends('admin.master')

@section('content')
    @include('admin._patterns._search')

    <form action="{{ action('Admin\AnimeController@indexUpdate') }}"
          method="post"
          id="items-list">

        <input type="hidden"
               name="_token"
               value="{{ csrf_token() }}">
        {{ method_field('post') }}

        <div id="animeVue">
            <table>
                <thead>
                <tr>
                    <th>id</th>
                    <th></th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Visits</th>
                    <th></th>
                </tr>
                </thead>

                <tbody v-if="searchStatus == false">
                @forelse ( $anime as $an )
                    <tr>
                        <td>{{ $an->id }}</td>
                        <td>
                            <input type="hidden"
                                   name="options[{{ $an->id }}]"
                                   value="0">
                            <input type="checkbox"
                                   data-id="{{ $an->id }}"
                                   id="options-label-{{ $an->id }}"
                                   name="options[{{ $an->id }}]"
                                   value="1">
                            <label for="options-label-{{ $an->id}}"></label>
                        </td>
                        <td><img src="{{ asset('images/anime/'.$an->image_name) }}" width="50" height="50"></td>
                        <td>{{ $an->name }}</td>
                        <td>{{ $an->status }}</td>
                        <td>{{ $an->visits }}</td>
                        <td><a href="{{ action('Admin\AnimeController@edit', ['id' => $an->id]) }}"
                               class="waves-effect waves-light btn edit-button">Edit</a></td>
                    </tr>
                @empty
                @endforelse
                </tbody>
                <tbody v-else
                       v-for="anime in searchData">
                <tr>
                    <td>@{{ anime.id }}</td>
                    <td>
                        <input type="hidden"
                               :name="'options['+anime.id+']'"
                               value="0">
                        <input type="checkbox"
                               :data-id="anime.id"
                               :id="'options-label-'+anime.id+''"
                               :name="'options['+anime.id+']'"
                               value="1">
                        <label :for="'options-label-'+anime.id+''"></label>
                    </td>
                    <td><img :src="'http://anime-music.ru/images/anime/'+anime.image_name+''" width="50" height="50"></td>
                    <td>@{{ anime.name }}</td>
                    <td>@{{ anime.status }}</td>
                    <td>@{{ anime.visits }}</td>
                    <td><a :href="'http://anime-music.ru/admin/anime/'+anime.id+'/edit'"
                           class="waves-effect waves-light btn edit-button">Edit</a></td>
                </tr>
                </tbody>
            </table>
            <input type="hidden"
                   name="action"
                   value="delete">

            <div class="pagination-container">
                {{ $anime->links() }}
            </div>

            <div class="select-option">
                <div class="input-field col s12">
                    <select class="selected-option"
                            id="items-list-action">
                        <option value="delete"
                                data-id="delete">delete
                        </option>
                    </select>
                </div>
                <div class="row col s12 m12 l12">
                    <a href="#"
                       id="items-list-submit">Продолжить</a>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('other_footer_links')

    <script src="{{ asset('js/admin/admin-anime.js') }}"></script>
    <script src="{{ asset('js/admin/admin-materializeMain.js') }}"></script>

@endsection