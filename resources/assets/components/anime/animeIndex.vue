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
                div(v-for='a in sorted').row.col.s12.m12.l12.each-anime
                    h5.center-align {{ a.name }}
                    div.col.s12.m12.l12.each-anime_image
                        img(:src="a.anime_preview")
                        p.each-anime_description
                    div.col.s12.m12.l12.each-anime_bottom--panel
                        span.each-anime_bottom--panel__author
                            img(:src="a.author.avatar")
                        span.each-anime_bottom--panel__events
                            i.fa.fa-eye.left
                            span {{ a.visits }}
                        span(@click="likePost(a.anime_id)").each-anime_bottom--panel__likes
                            span(v-if='a.likes > 0')
                                i(style='color: #ee6e73').fa.fa-heart-o.left
                            span(v-else)
                                i.fa.fa-heart-o.left
                            span {{ a.likes }}
                        router-link(:to="a.anime_link")
                            i.fa.fa-arrow-circle-right.right

            div.col.s12.m12.l3.right-sidebar
                div.col.s12.m12.l12.right-block
                    h5.col.s12.m12.l12.title.center-align Сортировка
                    div.content-block
                        p
                            input(name="group1", type="radio", id="by_new", v-model="sortType", :value="+1")
                            label(for="by_new") Новые
                        p
                            input(name="group1", type="radio", id="by_old", v-model="sortType", :value="+0")
                            label(for="by_old") Старые
                div.col.s12.m12.l12.right-block
                    h5.col.s12.m12.l12.title.center-align Популярные
                    div.content-block
                        div(v-for="best in bestAnime").col.s12.m12.l12.best-anime_each
                            img(:src="best.anime_preview")
                            a(href="#") {{ best.name }}
</template>

<script>
    import axios from 'axios';
    import store from '../../store/store.js';
    import indexBlockInfo from '../../components/indexBlockInfo.vue';
    import {mapState, mapActions} from 'vuex';
    import {likePost} from '../../modules/like.js';

    export default {
        components: {
            indexBlockInfo
        },
        data() {
            return {
                anime: [],
                bestAnime: [],
                skip: 0,
                offset: 0,
                sortType: 1,
            }
        },
        computed: {
            ...mapState(
                {
                    user: state => state.userStore.user
                }
            ),
            sorted: ({anime, sortType}) => !!anime ? anime.sort(
                    (a, b) => sortType == 1 ? new Date(a.created_at.date) : new Date(b.created_at.date)
                ) : null
        },
        methods: {
            ...mapActions(
                [
                    'changeInfo'
                ]
            ),
            async likePost(id) {
                let liked = await likePost(id, 'anime', this.user.id);

                if (true == liked) {
                    alertify.notify('Вы успешно оценили запись', 'success', 3);
                    for (let k in this.anime) {
                        if (this.anime[k].anime_id == id) {
                            this.anime[k].likes++;
                        }
                    }
                }
            },
        },
        mounted() {
            // anime
            axios.get(
                'api/anime/get', {
                    skip: this.skip
                }
            ).then(
                (response) => {
                    this.offset++;
                    this.skip = self * 20;
                    this.anime = response.data.response;
                }
            ).catch(
                (error) => {
                    console.log(error.response.data.error_data);
                }
            );

            // best anime
            axios.get(
                '/api/anime/best', {
                    responseType: 'json'
                }
            ).then(
                (response) => {
                    this.bestAnime = response.data.response;
                }
            ).catch(
                (error) => {
                    console.log(error.respons.data.error_data)
                }
            );
        },
    }
</script>