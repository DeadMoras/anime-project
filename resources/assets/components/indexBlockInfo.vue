<template lang="pug">
    div
        div.row.col.s12.m12.l4
            div.title
                h5.center-align Новые юзеры
            ul.collection
                li(v-for='user in lastUsers').collection-item.avatar
                    img(:src="'/images/user/'+user.name").circle
                    span.title {{ user.login }}
                    a(href='#!').secondary-content
                        i.fa.fa-user-circle
        div.row.col.s12.m12.l4
            div.title
                h5.center-align Последние Темы
            ul.collection
                li.collection-item.avatar
                    img(src="").circle
                    span.title Title
                    a(href='#!').secondary-content
                        i.fa.fa-user-circle
        div.row.col.s12.m12.l4
            div.title
                h5.center-align Обновления манги
            ul.collection
                li(v-for='manga in lastManga').collection-item.avatar
                    img(:src="'/images/manga/'+manga.image").circle
                    span.title {{ manga.name }}
                    a(href='#!').secondary-content
                        i.fa.fa-user-circle
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                lastUsers: [],
                lastThemes: [],
                lastManga: []
            }
        },
        mounted() {
            let self = this;
            axios.get('/index-statistic').then(function (response) {
                self.lastUsers = response.data.users;
                self.lastManga = response.data.manga;
            }).catch(function (error) {
            });
        },
    }
</script>