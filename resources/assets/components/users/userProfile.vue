<template lang="pug">
    div
        div(v-if="loading")
            div.progress
                div.indeterminate

        div.row.col.s12.m12.l12.user-profile_container
            div.parallax-container.row.col.s12.m12.l12.background-profile
                div.parallax
                    img(src="https://pp.vk.me/c604318/v604318903/44c22/XKw6IdnZ_xk.jpg", class="responsive-img")
            div.row.col.s12.m12.l12.user-profile_content
                div.col.s12.m12.l4.user-avatar
                    img(:src="user.avatar")
                    input(type="file" name="avatarUser" id="avatarUser" class="inputfile" accept=".png, .jpg",
                          @change="")
                    div.labelForChangeImg
                        label(for="avatarUser").labelForChangeImg1
                            i.fa.fa-cloud
                div.col.s12.m12.l8.profile_tabs
                    ul.tabs
                        li.tab.col.s3
                            a(href="#info").active Информация
                        li.tab.col.s3
                            a(href="#friends") Друзья
                        li.tab.col.s3
                            a(href="#reviews") Рецензии
                    div.row.col.s12.m12.l12.buttons
                        span(v-if="false != userInfo && userInfo.id != user.user_id")
                            span(v-if="false == user.current_user_friend")
                                a.waves-effect.waves-light.btn Добавить в друзья
                            span(v-else-if="true == user.current_user_friend")
                                a.waves-effect.waves-light.btn Удалить из друзей
                            span(v-else-if="'online user wait' == user.current_user_friend")
                                a.waves-effect.waves-light.btn Отписаться
                            span(v-else-if="'online user cancel' == user.current_user_friend")
                                a.waves-effect.waves-light.btn Принять
                            a.waves-effect.waves-light.btn Написать сообщение
            div.row.col.s12.m12.l12.user-content
                div#info.col.s12
                    div.row.col.s12.m12.l6.first-block
                        p.row.col.s12.m4.l12
                            | Логин
                            span {{ user.login }}
                        p.row.col.s12.m4.l12
                            | Вк
                            span(v-if="user.vk") 
                                | {{ user.vk }}
                            span(v-else)
                                | Пусто
                        p.row.col.s12.m4.l12
                            | Группа
                            span {{ user.role }}
                    div.row.col.s12.m12.l6.second-block
                        p.row.col.s12.m4.l12
                            | Город
                            span(v-if="user.city")
                                | {{ user.city }}
                            span(v-else)
                                | Пусто
                        p.row.col.s12.m4.l12
                             | Пол
                             span {{ user.sex }}
                        p.row.col.s12.m4.l12
                            | Репутация
                            span {{ user.reputation }}
                div#friends.col.s12
                    ul.row.col.s12.m12.l12
                        li(v-for="friends in user.friends").col.s12.m12.l3
                            span(v-if="user.user_id == friends.from_user_id")
                                img(:src="'http://anime-music.ru//images/user/' + friends.to_user_image")
                                p {{ friends.to_user_login }}
                            span(v-else)
                                img(:src="'http://anime-music.ru//images/user/' + friends.from_user_image")
                                p {{ friends.from_user_login }}
                div#reviews.col.s12
                    ul.row.col.s12.m12.l12
                        li(v-for="review in user.reviews").col.s12.m12.l12
                            img(:src="review.anime_preview")
                            span {{ review.comment }}

        div.row.col.s12.m12.l12.user-anime
            ul.tabs
                li.tab.col.s3
                    a(href="#watching").active Смотрю
                li.tab.col.s3
                    a(href="#will_watch") Буду смотреть
                li.tab.col.s3
                    a(href="#watched") Просмотрено
                li.tab.col.s3
                    a(href="#favorite") Любимое
            div.row.col.s12.m12.l12.background
            div#watching.col.s12
                ul(v-if="0 != user.list.watching.length").row.col.s12.m12.l12
                    li(v-for="watching in user.list.watching").row.col.s12.m12.l12
                        img(:src="watching.image_preview")
                        span {{ watching.anime_name }}
                        div(v-if="userInfo != false && userInfo.id == user.user_id")
                            i.fa.fa-trash-o
            div#will_watch.col.s12
                ul(v-if="0 != user.list.will_watch.length").row.col.s12.m12.l12
                    li(v-for="will_watch in user.list.will_watch").row.col.s12.m12.l12
                        img(:src="will_watch.image_preview")
                        span {{ will_watch.anime_name }}
                        div(v-if="userInfo != false && userInfo.id == user.user_id")
                            i.fa.fa-trash-o
            div#watched.col.s12
                ul(v-if="0 != user.list.watched.length").row.col.s12.m12.l12
                    li(v-for="watched in user.list.watched").row.col.s12.m12.l12
                        img(:src="watched.image_preview")
                        span {{ watched.anime_name }}
                        div(v-if="userInfo != false && user.id == userInfo.user_id")
                            i.fa.fa-trash-o
            div#favorite.col.s12
                ul(v-if="0 != user.list.favorite.length").row.col.s12.m12.l12
                    li(v-for="favorite in user.list.favorite").row.col.s12.m12.l12
                        img(:src="favorite.image_preview")
                        span {{ favorite.anime_name }}
                        div(v-if="userInfo != false && userInfo.id == user.user_id")
                          i.fa.fa-trash-o
</template>

<script>
    import axios from 'axios';
    import {mapState} from 'vuex';

    export default {
        data() {
            return {
                user: {
                    list: {
                        watching: [],
                        will_watch: [],
                        watched: [],
                        favorite: []
                    }
                },
                loading: false,
            }
        },
        created() {
            setTimeout(() => this.getData(), 1000);
        },
        watch: {
            '$route': 'getData'
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get('/api/user/profile/' + this.$route.params.id + '/' + this.userInfo.id).then(
                    (response) => {
                        this.user = response.data.response;
                        this.loading = false;
                    }
                ).catch((error) => alertify.notify('Возникла ошибка', 'error', 3));
            }
        },
        computed: {
            ...mapState(
                {
                    userInfo: state => state.userStore.user
                }
            )
        }
    }
</script>