<template lang="pug">
    div
        div.col.s12.m12.l12.index-with_anime--background
            div.col.s12.m12.l12.auth-register
                div(v-if="user != false")
                    span(style="color: #fff") Вы уже авторизованы. У Вас 0 новых уведомлений
                div(v-else)
                    a(href="#auth-modal").waves-effect.waves-light.btn.modal-auth_a Авторизация
                    a(href="/register").waves-effect.waves-light.btn Регистрация
            div.row.col.s12.m12.l12.background-description
            div.row.col.s12.m12.l12.background-slider
                <index-block-info></index-block-info>

        div.row.main-container

            div.col.s12.m12.l7.main-content
                div(v-for='a in anime').row.col.s12.m12.l12.each-anime
                    h5.center-align {{ a.name }}
                    div.col.s12.m12.l12.each-anime_image
                        img(:src="'/images/anime/' + a.anime_image_name")
                        p.each-anime_description
                    div.col.s12.m12.l12.each-anime_bottom--panel
                        span.each-anime_bottom--panel__author
                            img(:src="'/images/user/' + a.user_image_name")
                        span.each-anime_bottom--panel__events
                            i.fa.fa-eye.left
                            span {{ a.visits }}
                        span.each-anime_bottom--panel__likes
                            i.fa.fa-heart-o.left
                            span {{ a.likes }}
                        a(href="#")
                            i.fa.fa-arrow-circle-right.right

            div.col.s12.m12.l3.right-sidebar
                div.col.s12.m12.l12.right-block
                    h5.col.s12.m12.l12.title.center-align Сортировка
                    div.content-block
                        p
                            input(name="group1", type="radio", id="by_new")
                            label(for="by_new") Новые
                        p
                            input(name="group1", type="radio", id="by_old")
                            label(for="by_old") Старые
                div.col.s12.m12.l12.right-block
                    h5.col.s12.m12.l12.title.center-align Популярные
                    div.content-block
                        div.col.s12.m12.l12.best-anime_each
                            img(src="http://www.wallpaperscharlie.com/wp-content/uploads/2016/10/HD-Anime-Wallpapers-4.jpg")
                            a(href="#") Название аниме
                        div.col.s12.m12.l12.best-anime_each
                            img(src="http://www.wallpaperscharlie.com/wp-content/uploads/2016/10/HD-Anime-Wallpapers-4.jpg")
                            a(href="#") Название аниме
                        div.col.s12.m12.l12.best-anime_each
                            img(src="http://www.wallpaperscharlie.com/wp-content/uploads/2016/10/HD-Anime-Wallpapers-4.jpg")
                            a(href="#") Название аниме
</template>

<script>
    import axios from 'axios';
    import store from '../store/store.js';
    import indexBlockInfo from './indexBlockInfo.vue';
    import { mapState, mapActions } from 'vuex';

    export default {
        components: {
            indexBlockInfo
        },
        data() {
            return {
                anime: [],
                skip: 0,
                offset: 0,
            }
        },
        computed: {
            ...mapState({
                user: state => state.userStore.user
            })
        },
        methods: {
            ...mapActions([
                'changeInfo'
            ]),
        },
        mounted() {
            let self = this;
            axios.get('/get-anime', {
                skip: self.skip
            }).then(function(response) {
                self.offset++;
                self.skip = self * 20;
                self.anime = response.data;
            }).catch(function (error) {

            });
        },
    }
</script>