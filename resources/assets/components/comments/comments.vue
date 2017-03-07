<template lang="pug">
    div.row.col.s12.m12.l12.anime-watch_comments
            h5.center-align Комментарии
            div.row.col.s12.m12.l12.add-comment
                textarea(v-model="newComment.comment").row.col.s12.m12.l12
                div.reviews-or_not.col.s6.m6.l3
                    input(type="checkbox", v-model="newComment.review", value=1).filled-in#reviews-or_not--label
                    label(for="reviews-or_not--label") Рецензия
                div().reply-to_user--after__review.col.s6.m6.l3
                    div(v-if="0 != newComment.reply_to_user_id")
                        span Ответ для {{ newComment.reply_to_user_login }}
                        i(@click="cancelReply").fa.fa-remove
                div.col.s12.m12.l6.button
                    a(@click="addComment").waves-effect.waves-light.btn.col.s6.m6.l6 Добавить
            div.row.col.s12.m12.l12.comments
                ul.row.col.s12.m12.l12
                    div(v-if="0 != newComments.length")
                        span Новые комментарии {{ newComments.length }}
                    li(v-for="comment in comments",
                       :class="user.id == comment.answer_comment_user ? 'answer-to_user' : ''").row.col.s12.m12.l12
                        div.col.s12.m2.l2.avatar
                            img(:src="comment.user.avatar").row
                            a(v-if="user.id != comment.user.id"
                              @click="replyToComment(comment.comment_id, comment.user.id, comment.user.login)",
                              class="waves-effect waves-light btn col s12 m12 l12") Ответить
                        div.row.col.s12.m12.l9.comment-container
                            a(href="#") {{comment.user.login}}
                            div.row.col.s12.m12.l12
                                p {{ comment.comment }}
</template>

<script>
    import Vue from 'vue';
    import axios from 'axios';
    import VueSocketio from 'vue-socket.io';
    import {mapState} from 'vuex';
    import {showAlert} from "../../modules/alerts.js";
    import {checkToken, getToken} from '../../modules/token.js';
    Vue.use(VueSocketio, 'http://anime-music.ru:6001');

    export default {
        sockets: {
            comments: function (data) {
                if (data.route == this.$route.params.title) {
                    if (data.body.user.id != this.user.id) {
                        this.newComments.push(data.body);
                    }
                }
            }
        },
        data() {
            return {
                comments: [],
                newComments: [],
                newComment: {
                    comment: '',
                    reply_to_user_id: 0,
                    reply_to_comment: 0,
                    reply_to_user_login: '',
                    review: 0,
                    user_id: 0,
                    post_title: this.$route.params.title
                },
            }
        },
        created() {
            axios.post(
                '/api/comments/anime/get', {
                    seo_title: this.$route.params.title
                }
            ).then((response) => this.comments = response.data.response);
        },
        methods: {
            addComment() {
                if (!checkToken()) {
                    showAlert('success-js_button--error', 'Вы должны авторизоваться', 'fa-times')
                    return false;
                }

                if (1 > this.newComment.comment.length) {
                    return false;
                }

                this.newComment.user_id = this.user.id;
                let that = this;
                axios.post(
                    '/api/comments/anime/add', {
                        newComment: this.newComment,
                        access_token: getToken()
                    }
                ).then(
                    (response) => {
                        showAlert('success-js_button--success', 'Вы добавили комментарий', 'fa-check')
                        this.comments.push(response.data.response);
                        this.newComment.comment = '';
                        this.newComment.reply_to_user_id = 0;
                        this.newComment.reply_to_comment = 0;
                        this.newComment.reply_to_user_login = '';
                        this.newComment.review = 0;
                        this.newComment.user_id = 0;

                        this.$socket.emit(
                            'add-comment', {
                                title: this.$route.params.title,
                                comment: response.data.response
                            }
                        );
                    }
                ).catch(
                    (error) => {
                        if (423 == error.response.status) {
                            for (let k in error.response.data.error_data) {
                                showAlert('success-js_button--error', error.response.data.error_data[k], 'fa-times');
                            }
                        } else {
                            showAlert('success-js_button--error', error.response.data.error_data, 'fa-times');
                        }
                    }
                );
            },
            replyToComment(comment, user_id, user_login) {
                if (this.newComment.reply_to_comment == comment) {
                    return false;
                }

                this.newComment.reply_to_comment = comment;
                this.newComment.reply_to_user_id = user_id;
                this.newComment.reply_to_user_login = user_login;
            },
            cancelReply() {
                this.newComment.reply_to_user_id = 0;
                this.newComment.reply_to_user_login = '';
                this.newComment.reply_to_comment = 0;
            }
        },
        computed: {
            ...mapState(
                {
                    user: state => state.userStore.user
                }
            )
        }
    }
</script>