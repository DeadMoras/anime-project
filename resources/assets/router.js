import Vue from 'vue';
import VueRouter from 'vue-router';
import animeIndex from './components/animeIndex.vue';
import registerPage from './components/registerPage.vue';


Vue.use(VueRouter);

const routers = new VueRouter({
    routes: [
        {path: '/', component: animeIndex },
        { path: '/register', component: registerPage }
    ],
});

export default routers;