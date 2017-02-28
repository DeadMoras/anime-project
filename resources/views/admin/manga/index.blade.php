@extends('admin.master')

@section('content')
    @include('admin._patterns._search')

    <form action="{{ action('Admin\MangaController@indexUpdate') }}"
          method="post"
          id="items-list">

        <input type="hidden"
               name="_token"
               value="{{ csrf_token() }}">
        {{ method_field('post') }}

        <div id="mangaVue">
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
                @forelse ( $manga as $an )
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
                        <td><img src="{{ asset('images/manga/'.$an->image_name) }}" width="50" height="50"></td>
                        <td>{{ $an->name }}</td>
                        <td>{{ $an->status }}</td>
                        <td>{{ $an->visits }}</td>
                        <td><a href="{{ action('Admin\MangaController@edit', ['id' => $an->id]) }}"
                               class="waves-effect waves-light btn edit-button">Edit</a></td>
                    </tr>
                @empty
                @endforelse
                </tbody>
                <tbody v-else
                       v-for="manga in searchData">
                <tr>
                    <td>@{{ manga.id }}</td>
                    <td>
                        <input type="hidden"
                               :name="'options['+manga.id+']'"
                               value="0">
                        <input type="checkbox"
                               :data-id="manga.id"
                               :id="'options-label-'+manga.id+''"
                               :name="'options['+manga.id+']'"
                               value="1">
                        <label :for="'options-label-'+manga.id+''"></label>
                    </td>
                    <td><img :src="'http://anime-music.ru/images/manga/'+manga.image_name+''" width="50" height="50"></td>
                    <td>@{{ manga.name }}</td>
                    <td>@{{ manga.status }}</td>
                    <td>@{{ manga.visits }}</td>
                    <td><a :href="'http://anime-music.ru/admin/manga/'+manga.id+'/edit'"
                           class="waves-effect waves-light btn edit-button">Edit</a></td>
                </tr>
                </tbody>
            </table>
            <input type="hidden"
                   name="action"
                   value="delete">

            <div class="pagination-container">
                {{ $manga->links() }}
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

    <script src="{{ asset('js/admin/admin-manga.js') }}"></script>
    <script src="{{ asset('js/admin/admin-materializeMain.js') }}"></script>

@endsection