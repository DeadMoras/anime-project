Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');


let vm = new Vue({
    el: '#usersVue',
    data: {
        searchBundle: 'users',
        searchData: null,
        searchStatus: false,
    }
});
