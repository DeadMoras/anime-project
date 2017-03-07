<template lang="pug">
    div.col.s12.m12.l12.index-with_anime--background
        div.row.col.s12.m12.l12.register-container
            div.register-content.col.s11.m11.l4
                div(v-if="justWait")
                    div.preloader-wrapper.big.active
                        div.spinner-layer.spinner-blue-only
                            div.circle-clipper.left
                                div.circle
                            div.gap-patch
                                div.circle
                            div.circle-clipper.right
                                div.circle
                div.row
                    div.input-field.col.s12
                        input(type='email', name='email', id='register-email', v-model='user.email',
                                                    @input="justFilter(user.email, 'email', 3, 50)")
                        label(for='register-login') Ваш имеил
                    div.input-field.col.s12
                        input(type='text', name='login', id='register-login', v-model='user.login',
                                                    @input="justFilter(user.login, 'login', 4, 25)")
                        label(for='register-login') Ваш логин
                    div.input-field.col.s12
                        input(type='password', name='password', id='register-password', v-model='user.password',
                                                        @input="justFilter(user.password, 'password', 4, 30)")
                        label(for='register-login') Ваш пароль
                    div.input-field.col.s12
                        input(type="password", name="repeat-password", id="repeat-password",
                                        v-model="user.passwordConfirmation", @input="checkRePassword",
                                        v-bind:class="{ errorPasswordRepeat: errorPasswordRepeat }")
                        label(for='repeat-password') Подтверждение пароля
                    div.row.input-field.col.s12
                        input(type='checkbox', id='register-sex', v-model='user.sex')
                        label(for='register-sex') Мужчина/женщина
                    div.file-field.input-field.col.s12#register-image
                        div(v-if='image.length == 0', class='btn')
                            span Аватарка
                                input(type='file', @change='uploadImage')
                        div(v-if='image.length == 0', class='file-path-wrapper')
                            input(type='text', class='file-path')
                        div(v-else-if='imageResponse.length > 0', class='imageUploaded')
                            img(v-for='item in imageResponse', :src='item.imageName')
                            div.panel-image
                                a(class='waves-effect waves-light btn col s12', @click='removeImage()') Удалить
                    div.input-field.col.s12.social-icons
                        div(class='div-social_icon', @click="socialInput('registerVk')")
                            social-icon.fa.fa-vk
                        div(class='div-social_icon', @click="socialInput('registerSkype')")
                            social-icon.fa.fa-skype
                        div(class='div-social_icon', @click="socialInput('registerTwitter')")
                            social-icon.fa.fa-twitter
                        div(class='div-social_icon', @click="socialInput('registerFacebook')")
                            social-icon.fa.fa-facebook
                    div(v-if="socialIconsFor('registerVk')", class='social_icon-css')
                        div.input-field.col.s12#register-input_div--register-vk
                            input(type='text', id='register-input_vk', v-model='user.registerVk')
                            label(for='register-input_vk') Ваш вк
                    div(v-if="socialIconsFor('registerFacebook')", class='social_icon-css')
                        div.input-field.col.s12#register-input_div--register-facebook
                            input(type='text', id='register-input_facebook', v-model='user.registerFacebook')
                            label(for='register-input_facebook') Ваш фб
                    div(v-if="socialIconsFor('registerTwitter')", class='social_icon-css')
                        div.input-field.col.s12#register-input_div--register-twitter
                            input(type='text', id='register-input_twitter', v-model='user.registerTwitter')
                            label(for='register-input_twitter') Ваш твитер
                    div(v-if="socialIconsFor('registerSkype')", class='social_icon-css')
                        div.input-field.col.s12#register-input_div--register-skype
                            input(type='text', id='register-input_skype', v-model='user.registerSkype')
                            label(for='register-input_skype') Ваш скайп
                div(v-if='disabledButton', class='row col s12 ul-errors')
                    ul
                        li(v-for='item in error') {{ item.min }} {{ item.max }} {{ item.required }}
                        li {{ error.passwordError }}
                a(href='#', :class='errorButton', @click.prevent='justRegister()') Зарегистрироваться
</template>

<script>
    import Vue from 'vue';
    import { justLength } from '../../modules/validate.js';
    import { uploadImage } from '../../modules/uploadImage.js';
    import { removeImage } from '../../modules/uploadImage.js';
    import axios from 'axios';

    Vue.component('social-icon', {
        template: `
            <i aria-hidden="true"></i>
        `,
        data() {
            return {}
        }
    });
    export default {
        data() {
            return {
                image: [],
                imageResponse: [],
                pathToSaveImage: 'user',
                imageBundle: 'user',
                imageWidth: 500,
                imageHeight: 500,
                imageUploaded: false,
                user: {
                    login: '',
                    email: '',
                    password: '',
                    passwordConfirmation: '',
                    login: '',
                    sex: false,
                    registerVk: '',
                    registerFacebook: '',
                    registerTwitter: '',
                    registerSkype: '',
                },
                errorPasswordRepeat: false,
                error: {
                    email: {
                        required: 'Поле email обязательно для заполнения'
                    },
                    login: {
                        required: 'Поле login обязательно для заполнения'
                    },
                    password: {
                        required: 'Поле password обязательно для заполнения'
                    },
                },
                disabledButton: true,
                justWait: false,
                socialIcons: {
                    registerVk: false,
                    registerSkype: false,
                    registerTwitter: false,
                    registerFacebook: false,
                }
            }
        },
        methods: {
            checkRePassword() {
                if (this.user.password && this.user.password !== this.user.passwordConfirmation) {
                    this.errorPasswordRepeat = true;
                    this.error.passwordError = 'Пароли не совпадают';
                    this.disabledButton = true;
                } else {
                    if (!this.error.email && !this.error.login && !this.error.password) {
                        this.disabledButton = false;
                    }
                    this.errorPasswordRepeat = false;
                    delete this.error.passwordError;
                }
            },
            justFilter(value, nameInput, min, max) {
                justLength(this, value, nameInput, min, max);
            },
            justRegister() {
                axios.post('/register', {
                    user: this.user,
                    imageUploaded: this.imageUploaded,
                    imageResponse: this.imageResponse
                }).then(function (response) {
                    for ( let k in response.data ) {
                        Materialize.toast(response.data[k], 3000);
                    }
                }).catch(function (error) {
                    for(let k of error.response.data) {
                        Materialize.toast(k, 3000);
                    }
                });
            },
            socialInput(iconName) {
                if (!this.socialIcons.iconName) {
                    if (this.socialIcons[iconName] == false) {
                        this.socialIcons[iconName] = true;
                    } else {
                        this.socialIcons[iconName] = false;
                    }
                }
            },
            socialIconsFor(iconName) {
                if (this.socialIcons[iconName] == true) {
                    return true;
                } else {
                    return false;
                }
            },
            uploadImage(e) {
                uploadImage(e, this);
            },
            removeImage() {
                removeImage(this);
            }
        },
        computed: {
            errorButton: function () {
                return {
                    'col s12 btn': 'btn',
                    'disabled': this.disabledButton,
                }
            }
        }
    }
</script>