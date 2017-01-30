@extends('admin.master')

@section('content')

    <form action="{{ $is_new ? action('Admin\AnimeController@store') : action('Admin\AnimeController@update', ['id' => $anime->id]) }}"
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
                    @if ( $sameAnime )
                        <div class="input-field col s12">
                            <input type="text"
                                   value="{{ $sameAnime->name }}"
                                   disabled
                                   class="col s9">
                            <input type="checkbox"
                                   class="col s3"
                                   id="delete-same-anime"
                                   name="delete-same-anime"
                                   value="{{ $sameAnime->id }}">
                            <label for="delete-same-anime">Delete</label>
                        </div>
                    @else
                    <div class="row search-same">
                        <div class="input-field col s12">
                            <input type="text"
                                   v-model="searchSameInput"
                                   id="searchSameInput"
                                   class="col s7">
                            <label for="searchSameInput">Write name of anime</label>
                            <a class="waves-effect waves-light btn col s2"
                               @click="searchSameAnime()">Search</a>
                        </div>
                    </div>
                    <div class="row">
                        <div v-if="sameAnimeStatus">
                            <ul class="row col s12 m12 l12 same-anime_result">
                                <li v-for="item in sameAnimeResponse"
                                    class="col s6">
                                    <div class="input-field col s10">
                                        @{{ item.name }}
                                        <input type="radio"
                                               :id="'radioSameAnime-'+ item.id"
                                               name="sameAnime"
                                               :value="item.id"
                                               class="col s2">
                                        <label :for="'radioSameAnime-'+ item.id"></label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
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
                                       name="anime-new_link"
                                       id="anime-new_link">
                                <label for="anime-new_link">Write link</label>
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
                    <div class="row col s12 m12 l12 all-videos_container">
                        @if ( count($animeSeries) )
                            <div class="row series">
                                @foreach($animeSeries as $series)
                                    <div class="input-field col s5">
                                        <input type="text"
                                               class="col s10"
                                               value="{{ $series->link }}"
                                               disabled>
                                        <div class="col s2">
                                            <input type="hidden"
                                                   name="delete-series[{{ $series->id }}]"
                                                   value="0">
                                            <input type="checkbox"
                                                   id="delete-series-{{$series->id}}"
                                                   name="delete-series[{{ $series->id }}]"
                                                   value="1">
                                            <label for="delete-series-{{$series->id}}">Delete</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="row">
                            <div v-if="showInputLinkVideo"
                                 class="input-field col s6">
                                <input type="text"
                                       id="uploaded-video"
                                       name="uploaded-video"
                                       :value="newVideoLink">
                            </div>
                            <input type="hidden"
                                   name="image_mimeType"
                                   :value="imageResponse.imageType">
                            <input type="hidden"
                                   name="image_id"
                                   :value="imageResponse.imageId">
                            <input type="hidden"
                                   name="image_name"
                                   :value="imageResponse.imageName">
                        </div>
                    </div>
                </div>
                <div id="seo" class="col s12">
                    @include('seo.main', ['data' => $anime])
                </div>
                <div id="image" class="col s12">
                    <div class="input-field col s12 edit-anime_image">
                        @if ( $is_new == false )
                            <img src="{{ asset('images/anime/'.$anime->image_name) }}" alt="">
                            <input type="hidden"
                                   name="delete-uploaded_image"
                                   value="0">
                            <input type="checkbox"
                                   name="delete-uploaded_image"
                                   id="delete-uploaded_image"
                                   value="1">
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
    <script src="{{ asset('js/admin/admin-animeCrup.js') }}"></script>

@endsection