import Vue from 'vue';
import store from './store/store.js';
import footerComponent from './components/patterns/footerComponent.vue';
import headerComponent from './components/patterns/headerComponent.vue';
import router from './router.js';
import { mapActions } from 'vuex';

const app = new Vue({
    store,
    router,
    el: '#app',
    components: {
        footerComponent, headerComponent
    },
    methods: {
        ...mapActions([
            'changeInfo'
        ])
    },
    mounted() {
        let self = this;
    }
});