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
                    div(v-if="0 != newComments.length" @click="showNewComments").new-comments_sockets
                        span Новые комментарии {{ newComments.length }}
                    li(v-for="comment in comments",
                       :class="user.id == comment.answer_comment_user ? 'answer-to_user' : ''").row.col.s12.m12.l12
                        div.col.s12.m2.l2.avatar
                            img(:src="comment.user.avatar").row
                            a(v-if="user.id != comment.user.id"
                              @click="replyToComment(comment.comment_id, comment.user.id, comment.user.login)",
                              class="waves-effect waves-light btn col s12 m12 l12") Ответить
                        div.row.col.s12.m12.l9.comment-container
                            router-link(:to="'/user/id/' + comment.user.id") {{comment.user.login}}
                            div.row.col.s12.m12.l12
                                p {{ comment.comment }}
                div(v-if="10 <= comments.length").more-comments
                    a(v-if="false != paginationStatus" @click="getComments").waves-light.waves-effect.btn Еще комментарии
</template>

<script>
    import Vue from 'vue';
    import axios from 'axios';
    import VueSocketio from 'vue-socket.io';
    import {mapState} from 'vuex';
    import {showAlert} from "../../modules/alerts.js";
    import {checkToken, getToken} from '../../modules/token.js';
    Vue.use(VueSocketio, 'http://anime-music.ru:6001');
    let skip, offset = 0;

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
                paginationStatus: true
            }
        },
        created() {
            this.getComments()
        },
        methods: {
            getComments() {
                axios.post(
                    '/api/comments/anime/get', {
                        seo_title: this.$route.params.title,
                        skip_pagination: skip,
                    }
                ).then(
                    (response) => {
                        offset++;
                        skip = offset * 10;
                        if (0 < this.comments.length) {
                            this.comments.push(...response.data.response);
                        } else {
                            this.comments = response.data.response;
                        }
                        this.paginationStatus = true;
                    }
                ).catch(
                    (error) => {
                        showAlert('success-js_button--error', error.response.data.error_data, 'fa-times');
                        this.paginationStatus = false;
                    }
                );
            },
            addComment() {
                if (!checkToken()) {
                    alertify.notify('Вы должны авторизоваться', 'error', 3);
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
                        alertify.notify('Вы добавили комментарий', 'success', 2);
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
                                alertify.notify(error.response.data.error_data[k], 'error', 2);
                            }
                        } else {
                            alertify.notify(error.response.data.error_data, 'error', 3);
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
            },
            showNewComments() {
                if (0 == this.newComments.length) {
                    return false;
                }

                this.comments.push(...this.newComments);
                this.newComments = [];
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