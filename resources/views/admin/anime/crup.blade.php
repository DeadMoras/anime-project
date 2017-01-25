@extends('admin.master')

@section('content')

    <form action="{{ $is_new ? action('Admin\AnimeController@store') : action('Admin\AnimeController@update', ['id' => $anime->product_id]) }}"
          method="post">

        <div id="animeCrup">
            <div v-if="justWait">
                <div class="preloader-wrapper big active">
                    <div class="spinner-layer spinner-blue-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
            </div>

            <input name="_method"
                   type="hidden"
                   value="{{ $is_new ? 'post' : 'put' }}">

            {{ csrf_field() }}

            <div class="row">
                <div class="col s12">
                    <ul class="tabs">
                        <li class="tab col s3"><a href="#anime">Anime</a></li>
                        <li class="tab col s3"><a class="active" href="#series">Series</a></li>
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
                <div id="series" class="col s12">
                    <div class="row col s12 m12 l12 choose-buttons_upload">
                        <a class="waves-effect waves-light btn col s5"
                        @click="showLinkBool = !showLinkBool">Link</a>
                        <a class="waves-effect waves-light btn col s5"
                        @click="showIconsBool = !showIconsBool">File</a>
                    </div>
                    <div class="row col s12 m12 l12 choose-buttons_content">
                        <div v-if="showLinkBool"
                             class="col s6">
                            <div class="input-field col s11">
                                <input type="text"
                                       name="anime-each_link"
                                       id="anime-each_link">
                                <label for="anime-each_link">Write link</label>
                            </div>
                        </div>
                        <div v-if="showIconsBool"
                             class="col s6">
                            <div class="file-field input-field col s4">
                                <div class="btn">
                                    <span>File</span>
                                    <input type="file"
                                    @change="uploadVideo">
                                </div>
                            </div>
                            <div class="input-field col s4">
                                <input type="text"
                                       id="file_title"
                                       v-model="file.title">
                                <label for="file_title">Title</label>
                            </div>
                            <div class="input-field col s4">
                                <input type="text"
                                       id="file_description"
                                       v-model="file.description">
                                <label for="file_description">Description</label>
                            </div>
                            <div class="input-field col s4">
                                <input type="checkbox"
                                       id="file_wallpost"
                                       v-model="file.wallpost"
                                       value="1">
                                <label for="file_wallpost">Wall post</label>
                            </div>
                            <div class="input-field col s4">
                                <input type="text"
                                       id="file_group_id"
                                       v-model="file.group_id">
                                <label for="file_group_id">Group id</label>
                            </div>
                            <div class="input-field col s4">
                                <input type="text"
                                       id="file_album_id"
                                       v-model="file.album_id">
                                <label for="file_album_id">Album id</label>
                            </div>
                        </div>
                    </div>
                </div>
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
        </div>
    </form>

@endsection

@section('other_footer_links')

    <script src="{{ asset('js/admin/admin-materializeMain.js') }}"></script>
    <script src="{{ asset('js/admin/admin-animeCrup.js') }}"></script>

@endsection