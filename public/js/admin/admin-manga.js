Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

let vm = new Vue({
    el: '#mangaVue',
    data: {
        searchBundle: 'manga',
        searchData: null,
        searchStatus: false,
    }
});
