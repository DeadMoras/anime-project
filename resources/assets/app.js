import Vue from 'vue';
import store from './store/store.js';
import footerComponent from './components/patterns/footerComponent.vue';
import headerComponent from './components/patterns/headerComponent.vue';
import router from './router.js';
import {mapActions} from 'vuex';
import axios from 'axios';
import {getToken, checkToken} from './modules/token.js';

const app = new Vue(
    {
        store,
        router,
        el: '#app',
        components: {
            footerComponent, headerComponent
        },
        methods: {
            ...mapActions(
                [
                    'changeInfo'
                ]
            )
        },
        mounted() {
            if (checkToken()) {
                axios.post(
                    '/api/user/get', {
                        access_token: getToken(),
                        user_id: false == store.state.userStore.user ? 0 : store.state.userStore.user.id
                    }
                ).then(
                    (response) => {
                        this.changeInfo(response.data.response);
                    }
                ).catch(
                    (error) => {
                        console.log(error.response.data)
                    }
                );
            }
        }
    }
);