@extends('admin.master')

@section('content')

    <form action="{{ $is_new ? action('Admin\AnimeController@store') : action('Admin\AnimeController@update', ['id' => $anime->product_id]) }}"
          method="post">

        <input name="_method"
               type="hidden"
               value="{{ $is_new ? 'post' : 'put' }}">

        {{ csrf_field() }}

        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s3"><a class="active" href="#anime">Anime</a></li>
                    <li class="tab col s3"><a href="#series">Series</a></li>
                    <li class="tab col s3"><a href="#seo">Seo</a></li>
                    <li class="tab col s3"><a href="#image">Image</a></li>
                </ul>
            </div>
            <div id="anime" class="col s12 anime-crup">
                <div class="row">
                    <div class="input-field col s6">
                        <input type="text"
                               name="anime_name"
                               id="anime_name"
                               value="{{ old('anime_name', empty($anime->name) ? '' : $anime->name ) }}">
                        <label for="anime_name">Anime name</label>
                    </div>
                    <div class="input-field col s6">
                        <select name="anime_status">
                            <option value="" {{ empty($anime->status) ? '' : 'select' }}>не выбрана</option>
                            @foreach (config('anime.status') as $key => $value)
                                <option value="{{ $value }}"
                                        {{ !empty($anime->status) == $value ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        <label>Status</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <select name="anime_year">
                            <option value="" {{ empty($anime->year) ? '' : 'select' }}>не выбрана</option>
                            @foreach (config('anime.years') as $key => $value)
                                <option value="{{ $value }}"
                                        {{ !empty($anime->year) == $value ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        <label>year</label>
                    </div>
                    <div class="input-field col s6">
                        <select name="anime_age">
                            <option value="" {{ empty($anime->age) ? '' : 'select' }}>не выбрана</option>
                            @foreach (config('anime.age') as $key => $value)
                                <option value="{{ $value }}"
                                        {{ !empty($anime->age) == $value ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        <label>age</label>
                    </div>
                </div>
            </div>
            <div id="series" class="col s12">Test 2</div>
            <div id="seo" class="col s12">
                @include('seo.main', ['data' => $anime])
            </div>
            <div id="image" class="col s12">Test 3</div>
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
                    value="1">Update</button>
            <button class="waves-effect waves-light btn"
                    name="update_close"
                    value="1">Update and close</button>
            <button class="waves-effect waves-light btn"
                    name="close"
                    value="1">Close</button>
        </div>
    </form>

@endsection

@section('other_footer_links')

    <script src="{{ asset('js/admin/admin-materializeMain.js') }}"></script>

@endsection