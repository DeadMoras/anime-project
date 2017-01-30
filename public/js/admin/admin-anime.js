Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');


let vm = new Vue({
    el: '#animeVue',
    data: {
        searchBundle: 'anime',
        searchData: null,
        searchStatus: false,
    }
});
