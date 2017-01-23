@extends('user.master')

@section('title', 'Регистрация')

@section('content')

    <div class="col s12 m12 l12 index-with_anime--background">
        <div class="row col s12 m12 l12 register-container">
            <div class="register-content col s11 m11 l4"
                 id="register-form">
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
                <div class="row">
                    <div class="input-field col s12">
                        <input type="email"
                               name="email"
                               id="register-email"
                               v-model="user.email"
                        @input="justLength(user.email, 'email', 5, 30)">
                        <label for="register-email">Ваш e-mail</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text"
                               name="login"
                               id="register-login"
                               v-model="user.login"
                        @input="justLength(user.login, 'login', 4, 25)">
                        <label for="register-login">Ваш логин</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="password"
                               name="password"
                               id="register-password"
                               v-model="user.password"
                        @input="justLength(user.password, 'password', 4, 30)"
                        @change="checkRePassword">
                        <label for="register-password">Ваш пароль</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="password"
                               name="repeat-password"
                               id="repeat-password"
                               v-model="user.passwordConfirmation"
                        @input="checkRePassword"
                        v-bind:class="{ errorPasswordRepeat: errorPasswordRepeat }">
                        <label for="repeat-password">Подтверждение пароля</label>
                    </div>
                    <div class="row input-field col s12">
                        <input type="checkbox"
                               id="register-sex"
                               v-model="user.sex">
                        <label for="register-sex">Мужчина/женщина</label>
                    </div>
                    <div class="file-field input-field col s12"
                         id="register-image">
                        <div class="btn"
                             v-if="!image">
                            <span>Аватарка</span>
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
                    <div class="input-field col s12 social-icons">
                        <div class="div-social_icon"
                             @click="socialInput('registerVk')">
                            <social-icon class="fa fa-vk">
                            </social-icon>
                        </div>
                        <div class="div-social_icon"
                             @click="socialInput('registerSkype')">
                            <social-icon class="fa fa-skype">
                            </social-icon>
                        </div>
                        <div class="div-social_icon"
                             @click="socialInput('registerTwitter')">
                            <social-icon class="fa fa-twitter">
                            </social-icon>
                        </div>
                        <div class="div-social_icon"
                             @click="socialInput('registerFacebook')">
                            <social-icon class="fa fa-facebook">
                            </social-icon>
                        </div>

                        <div v-if="socialIconsFor('registerVk')"
                             class="social_icon-css">
                            <div class="input-field col s12"
                                 id="register-input_div--register-vk">
                                <input type="text"
                                       id="register-input_vk"
                                       v-model="user.registerVk">
                                <label for="register-input_vk">Ваш vk</label>
                                </div>
                        </div>
                        <div v-if="socialIconsFor('registerSkype')"
                             class="social_icon-css">
                            <div class="input-field col s12"
                                 id="register-input_div--register-skype">
                                <input type="text"
                                       id="register-input_skype"
                                       v-model="user.registerSkype">
                                <label for="register-input_skype">Ваш skype</label>
                                </div>
                        </div>
                        <div v-if="socialIconsFor('registerFacebook')"
                             class="social_icon-css">
                            <div class="input-field col s12"
                                 id="register-input_div--register-facebook">
                                <input type="text"
                                       id="register-input_facebook"
                                       v-model="user.registerFacebook">
                                <label for="register-input_facebook">Ваш facebook</label>
                                </div>
                        </div>
                        <div v-if="socialIconsFor('registerTwitter')"
                             class="social_icon-css">
                            <div class="input-field col s12"
                                 id="register-input_div--register-twitter">
                                <input type="text"
                                       id="register-input_twitter"
                                       v-model="user.registerTwitter">
                                <label for="register-input_twitter">Ваш twitter</label>
                                </div>
                        </div>
                    </div>
                    <div class="row col s12 ul-errors" v-if="disabledButton">
                        <ul>
                            <li v-for="item in error">
                                @{{ item.min }}
                                @{{ item.max }}
                                @{{ item.required }}
                            </li>
                            <li>
                                @{{ error.passwordError }}
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#"
                   :class="errorButton"
                   @click.prevent="justRegister()">Зарегистрироваться</a>
            </div>
        </div>
    </div>

@endsection

@section('other_footer_links')

    <script src="{{ asset('js/register-vue.js') }}"></script>
    <script src="{{ asset('js/materialize-main.js') }}"></script>

@endsection