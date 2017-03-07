<template lang="pug">
    div
        div.main-header
            ul.nav-mobile.right.hide-on-med-and-down
                li
                    <router-link to="/register">Регистрация</router-link>
                li
                    a(href='#') Ссылка
                li
                    a(href='#') Ссылка
                li
                    a(href='#') Ссылка
                li
                    form(action='/logout', method='post')
                        button.header-button_logout Выход
        ul#dropdown1.dropdown-content.header-menu
            li
                a(href='#') one
            li
                a(href='#') two
            li.divider
            li
                a(href='#') three
        div(v-if="user != false")
            a(href="#", data-activates="dropdown1").dropdown-button
                div.col.s3.m3.l4.right-header_profile
                    img(:src="user.avatar")
                    span {{ user.login }}
</template>

<script>
    import store from '../../store/store.js';
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
            }
        },
        computed: {
            ...mapState(
                {
                    user: state => state.userStore.user
                }
            )
        },
    }
</script>