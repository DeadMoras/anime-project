Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

let vm = new Vue({
    el: '#mainContent',
    data: {
        statistic: [
            { name: 'Пользователи', count: 32 },
            { name: 'Форум', count: 2 },
            { name: 'Аниме', count: 31 },
            { name: 'Манга', count: 521 },
            { name: 'Жалоб', count: 105 },
        ],
        searchBundle: 'complain',
        searchData: [],
        searchStatus: false
    }
});