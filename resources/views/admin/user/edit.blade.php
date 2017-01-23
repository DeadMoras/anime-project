@extends('admin.master')

@section('content')

    <h5 class="center-align h5-low">Edit profile {{ $user->login }}</h5>
    <form action="{{ action('Admin\UsersController@update', ['id' => $user->id]) }}"
          method="post">
        <input name="_method"
               type="hidden"
               value="PUT">
        {{ csrf_field() }}
        <div class="row col s12 m12 l12 user-edit_container">
            <div class="input-field col s12 m12 l6">
                <input type="text"
                       class="col s12"
                       id="user_email"
                       name="user_email"
                       value="{{ $user->email }}">
                <label for="user_email">User email</label>
            </div>
            <div class="input-field col s12 m12 l6">
                <input type="text"
                       class="col s12"
                       id="user_login"
                       name="user_login"
                       value="{{ $user->login }}">
                <label for="user_login">user login</label>
            </div>
            <div class="input-field col s12 m12 l3">
                <input type="text"
                       class="col s12"
                       id="user_vk"
                       name="user_vk"
                       value="{{ $user->vk }}">
                <label for="user_vk">User vk</label>
            </div>
            <div class="input-field col s12 m12 l3">
                <input type="text"
                       class="col s12"
                       id="user_facebook"
                       name="user_facebook"
                       value="{{ $user->facebook }}">
                <label for="user_facebook">User facebook</label>
            </div>
            <div class="input-field col s12 m12 l3">
                <input type="text"
                       class="col s12"
                       id="user_twitter"
                       name="user_twitter"
                       value="{{ $user->twitter }}">
                <label for="user_twitter">User twitter</label>
            </div>
            <div class="input-field col s12 m12 l3">
                <input type="text"
                       class="col s12"
                       id="user_skype"
                       name="user_skype"
                       value="{{ $user->skype }}">
                <label for="user_skype">User skype</label>
            </div>
            <div class="input-field col s12 m12 l3">
                <input type="hidden"
                       name="user_sex"
                       value="0">
                <input type="checkbox"
                       name="user_sex"
                       id="user_sex"
                       value="1"
                        {{ $user->sex == 0 ? '' : 'checked' }}>
                <label for="user_sex">Man/Wooman</label>
            </div>
            <div class="input-field col s12 m12 l3">
                <input type="hidden"
                       name="user_confirmed"
                       value="0">
                <input type="checkbox"
                       name="user_confirmed"
                       id="user_confirmed"
                       value="1"
                        {{ $user->confirmed == 1 ? 'checked' : '' }}>
                <label for="user_confirmed">Confirmed</label>
            </div>
            <div class="input-field col s12 m12 l6">
                <select name="user_role">
                    @foreach (config('users.roles') as $key => $value)
                        <option value="{{ $key }}" {{ $user->role == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                <label>User role</label>
            </div>
            <div class="input-field col s12 m12 l4">
                @if ( !empty($user->name) )
                    <img src="{{ asset('images/user/'.$user->name) }}"
                         class="user_image">
                    <input type="hidden"
                           name="user_image_delete"
                           value="0">
                    <input type="checkbox"
                           id="user_image_delete"
                           name="user_image_delete"
                           value="1">
                    <label for="user_image_delete">Delete user image</label>
                @else
                    <img src="{{ asset('images/user/default.jpg') }}"
                         class="user_image">
                @endif
            </div>
        </div>

        <div class="form-buttons">
            <input type="hidden"
                   name="update"
                   value="0">
            <input type="hidden"
                   name="update_close"
                   value="0">
            <input type="hidden"
                   name="close"
                   value="0">

            <button class="waves-effect waves-light btn"
                    name="update"
                    value="1">Update
            </button>
            <button class="waves-effect waves-light btn"
                    name="update_close"
                    value="1">Update and close
            </button>
            <button class="waves-effect waves-light btn"
                    name="close"
                    value="1">Close
            </button>
        </div>
    </form>

@endsection

@section('other_footer_links')

    <script src="{{ asset('js/admin/admin-materializeMain.js') }}"></script>

@endsection