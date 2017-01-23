@extends('admin.master')

@section('content')
    @include('admin._patterns._search')

    <form action="{{ action('Admin\UsersController@indexUpdate') }}"
          method="post"
          id="items-list">

        <input type="hidden"
               name="_token"
               value="{{ csrf_token() }}">
        {{ method_field('post') }}

        <div id="usersVue">
            <table>
                <thead>
                <tr>
                    <th>id</th>
                    <th></th>
                    <th>Image</th>
                    <th>Role</th>
                    <th>Login</th>
                    <th>Confirmed</th>
                    <th></th>
                </tr>
                </thead>

                <tbody v-if="searchStatus == false">
                        @forelse ( $users as $user )
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <input type="hidden"
                                           name="options[{{ $user->id }}]"
                                           value="0">
                                    <input type="checkbox"
                                           data-id="{{ $user->id }}"
                                           id="options-label-{{ $user->id }}"
                                           name="options[{{ $user->id }}]"
                                           value="1">
                                    <label for="options-label-{{ $user->id}}"></label>
                            </td>
                                <td><img src="{{ asset('images/user/'.$user->name ) }}" width="50" height="50"></td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->login }}</td>
                                <td>
                                    @if ( $user->confirmed == 1 )
                                        Confirmed
                                    @else
                                        Not confirm
                                    @endif
                                </td>
                                <td><a href="{{ action('Admin\UsersController@edit', ['id' => $user->id]) }}"
                                           class="waves-effect waves-light btn edit-button">Edit</a></td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                <tbody v-else
                       v-for="user in searchData">
                    <tr>
                        <td>@{{ user.id }}</td>
                        <td>
                            <input type="hidden"
                                   :name="'options['+user.id+']'"
                                   value="0">
                            <input type="checkbox"
                                   :data-id="user.id"
                                   :id="'options-label-'+user.id+''"
                                   :name="'options['+user.id+']'"
                                   value="1">
                            <label :for="'options-label-'+user.id+''"></label>
                        </td>
                        <td><img :src="'http://anime-music.ru/images/user/'+user.name+''" width="50" height="50"></td>
                        <td>@{{ user.role }}</td>
                        <td>@{{ user.login }}</td>
                        <td>
                            <span v-if="user.confirmed == 1">
                                Confirmed
                            </span>
                            <span v-else>
                                Not confirm
                            </span>
                        </td>
                        <td><a :href="'http://anime-music.ru/admin/users/'+user.id+'/edit'"
                                   class="waves-effect waves-light btn edit-button">Edit</a></td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden"
                   name="action"
                   value="notConfirm">

            <div class="pagination-container">
                {{ $users->links() }}
            </div>

            <div class="select-option">
                <div class="input-field col s12">
                    <select class="selected-option"
                            id="items-list-action">
                        <option value="notConfirm"
                                data-id="notConfirm">notConfirm
                        </option>
                        <option value="confirm"
                                data-id="confirm">Confirm
                        </option>
                        <option value="delete"
                                data-id="delete">Delete
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

    <script src="{{ asset('js/admin/admin-users.js') }}"></script>
    <script src="{{ asset('js/admin/admin-materializeMain.js') }}"></script>

@endsection