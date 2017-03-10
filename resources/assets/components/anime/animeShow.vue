<template lang="pug">
    div
        div(v-if="loading")
            div.progress
                div.indeterminate

        div.row.col.s12.m12.l12.index-with_anime--background
            div.row.col.s12.m12.l12.about-anime
                div.row.col.s12.m12.l6.left-content
                    div.anime-title
                        h5.center-align {{ anime.name_anime }}
                    div.row.col.s12.m12.l12.all-about_anime
                        p.row.col.s12.m12.l12
                            | Статус:
                            span {{ anime.status }}
                        p.row.col.s12.m12.l12
                            | Возрастное ограничение:
                            span от {{ anime.age }} лет
                        p.row.col.s12.m12.l12
                            | Год:
                            span {{ anime.year }}
                        p.row.col.s12.m12.l12
                            | Серий:
                            span {{ Array.from(anime.series).length }}
                        p.row.col.s12.m12.l12
                            | Жанры:
                            span(v-for="genres in anime.genres")
                                a(href="#") {{ genres.genre_name }}
                        a(@click="openSameAnime = !openSameAnime").waves-effect.col.s12.same-anime_button Порядок просмотра
                        div(v-if="openSameAnime").same-anime
                            span(v-if="anime.same_anime")
                                a(href="#", v-for="sameAnime in anime.same_anime") {{ sameAnime.anime_name }}
                div.row.col.s12.m12.l6.right-content
                    div.anime-poster
                        img(:src="anime.anime_preview")
        div.row.col.s12.m12.l12.anime-player
            ul.col.s11.m12.l10#forQuerySelector
                li(v-for="(page, index) in anime.series",
                    @click="watchAnime(page.id)",
                    :class="page.active == true ? 'active' : ''")
                    | {{ index }}
            div.col.s12.m12.l2.buttons-to_anime
                icons-profile(:postId="anime.id")

        div.row.main-container

            div.col.s12.m12.l7.main-content
                div(v-if="videoSrc").row.col.s12.m12.l12.video-player
                    iframe(:src="videoSrc" frameborder="0" scrolling="no" allowfullscreen, width="600" height="500")

                comments

            div.col.s12.m12.l3.right-sidebar.watch-anime_right
                div.col.s12.m12.l12.right-block
                    h5.col.s12.m12.l12.title.center-align Прочее
                    div.content-block.anime-watcher_other
                        p.col.s12
                            | Дата публикации:
                            span 12 октября
                        p.col.s12
                            | Просмотров:
                            span {{ anime.visits }}
                        a.waves-effect.waves-light.btn.col.s10.add_reputation
                            | Повысить репутацию
                            i.fa.fa-plus.right
                div.col.s12.m12.l12.right-block
                    h5.col.s12.m12.l12.title.center-align Автор
                    div.content-block.anime-watcher_about--user
                        p.col.s12
                            | Логин:
                            span {{ anime.author.login }}
                        p.col.s12
                            | Репутация:
                            span {{ anime.author.reputation }}
</template>

<script>
    import axios from 'axios';
    import iconsProfile from './iconsProfile.js';
    import {checkToken} from '../../modules/token.js';
    import comments from '../../components/comments/comments.vue';

    export default {
        components: {
            comments
        },
        data() {
            return {
                anime: [],
                loading: false,
                openSameAnime: false,
                videoSrc: '',
            }
        },
        created() {
            this.getData()
        },
        watch: {
            '$route': 'getData'
        },
        methods: {
            getData() {
                this.loading = true;

                axios.get('/api/anime/' + this.$route.params.title).then(
                    (response) => {
                        this.anime = response.data.response;
                        this.loading = false;
                    }
                ).catch(
                    (error) => {
                        console.log(error.response);
                    }
                );
            },
            watchAnime(id) {
                for(var k of this.anime.series) {
                    this.$set(k, 'active', false);
                    if( k.active == true ) {
                        this.videoSrc = '';
                        k.active = false;
                    }

                    if( k.id == id ) {
                        this.videoSrc = k.link;
                        this.$set(k, 'active', true);
                    }
                }
            }
        }
    }
</script>