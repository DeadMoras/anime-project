import Vue from 'vue';
import store from './store/store.js';
import registerPage from './components/registerPage.vue';
import authModal from './components/authModal.vue';

const app = new Vue({
    store,
    el: '#app',
    components: {
        registerPage, authModal
    }
});