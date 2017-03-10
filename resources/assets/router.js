import Vue from 'vue';
import VueRouter from 'vue-router';
import animeIndex from './components/anime/animeIndex.vue';
import registerPage from './components/auth/registerPage.vue';
import animeShow from './components/anime/animeShow.vue';
import userProfile from './components/users/userProfile.vue';

Vue.use(VueRouter);

const routers = new VueRouter(
    {
        mode: 'history',
        routes: [
            {path: '/', component: animeIndex},
            {path: '/register', component: registerPage},
            {path: '/anime/:title', component: animeShow},
            {path: '/user/id/:id', component: userProfile},
        ],
    }
);

export default routers;