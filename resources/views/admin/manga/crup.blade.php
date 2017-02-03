@extends('admin.master')

@section('content')

    <form action="{{ $is_new ? action('Admin\MangaController@store') : action('Admin\MangaController@update', ['id' => $manga->id]) }}"
          method="post">

        <div id="mangaCrup">
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
                        <li class="tab col s3"><a href="#manga">Manga</a></li>
                        <li class="tab col s3"><a class="active" href="#toms">Toms</a></li>
                        <li class="tab col s3"><a href="#seo">Seo</a></li>
                        <li class="tab col s3"><a href="#prev">Prev</a></li>
                    </ul>
                </div>
                <div id="manga" class="col s12 anime-crup">
                    <div class="row">
                        <div class="input-field col s6">
                            <input type="text"
                                   name="manga_name"
                                   id="manga_name"
                                   value="{{ old('manga_name', empty($manga->name) ? '' : $manga->name ) }}">
                            <label for="manga_name">Manga name</label>
                        </div>
                        <div class="input-field col s6">
                            <select name="manga_status">
                                <option value="" {{ empty($manga->status) ? '' : 'select' }}>не выбрана</option>
                                @foreach (config('anime.status') as $key => $value)
                                    <option value="{{ $value }}"
                                            {{ !empty($manga->status) == $value ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            <label>Status</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <select name="manga_year">
                                @foreach (config('anime.years') as $key => $value)
                                    <option value="{{ $value }}"
                                            {{ !isset($manga->year) == $value ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            <label>year</label>
                        </div>
                        <div class="input-field col s6">
                            <select name="manga_age">
                                <option value="" {{ empty($manga->age) ? '' : 'select' }}>не выбрана</option>
                                @foreach (config('anime.age') as $key => $value)
                                    <option value="{{ $value }}"
                                            {{ !empty($manga->age) == $value ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            <label>age</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input type="text"
                                   name="manga_author"
                                   id="manga_author"
                                   value="{{ old('manga_author', empty($manga->author) ? '' : $manga->author ) }}">
                            <label for="manga_author">Manga author</label>
                        </div>
                        <div class="input-field col s6">
                            <input type="text"
                                   name="manga_toms"
                                   id="manga_toms"
                                   value="{{ old('manga_toms', empty($manga->toms) ? '' : $manga->toms ) }}">
                            <label for="manga_toms">Manga toms</label>
                        </div>
                    </div>
                    <div class="row">
                        @include('admin.gentetics')
                    </div>
                </div>
                <div id="toms" class="col s12">
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
                                       name="anime-new_link"
                                       id="anime-new_link">
                                <label for="anime-new_link">Write link</label>
                            </div>
                        </div>
                        <div v-if="showIconsBool"
                             class="col s6">
                            <div class="file-field input-field col s12">
                                <div class="btn">
                                    <span>Images</span>
                                    <input type="file"
                                           multiple
                                           @change="vkImages">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="seo" class="col s12">
                    @include('seo.main', ['data' => $manga])
                </div>
                <div id="prev" class="col s12">
                    <div class="input-field col s12 edit-anime_image">
                        @if ( $is_new == false )
                            <img src="{{ asset('images/manga/'.$manga->image_name) }}" alt="">
                            <input type="hidden"
                                   name="delete-uploaded_image"
                                   value="0">
                            <input type="checkbox"
                                   name="delete-uploaded_image"
                                   id="delete-uploaded_image"
                                   value="{{ $manga->image_id }}">
                            <label for="delete-uploaded_image">Delete</label>
                        @endif
                    </div>
                    <div class="file-field input-field col s12"
                         id="register-image">
                        <div class="btn"
                             v-if="!image">
                            <span>Обложка</span>
                            <input type="file"
                            @change="uploadImage">
                        </div>
                        <div class="file-path-wrapper"
                             v-if="!image">
                            <input class="file-path"
                                   type="text">
                        </div>
                        <div class="imageUploaded"
                             v-else>
                            <img :src="image">
                            <div class="panel-image">
                                <a class="waves-effect waves-light btn col s12"
                                @click="removeImage">Удалить</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ( $is_new == false )
                <input type="hidden"
                       name="seo_id"
                       value="{{ $manga->seo_id }}">
            @endif

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
                        value="1">Create
                </button>
                <button class="waves-effect waves-light btn"
                        name="update_close"
                        value="1">Create and close
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
    <script src="{{ asset('js/admin/admin-mangaCrup.js') }}"></script>

@endsection