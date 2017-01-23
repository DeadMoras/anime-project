let vm = new Vue({
    el: '#manga',
    data: {
        filter: '',
        items: [
            {
                name: 'Название манги 1',
                img: 'https://i.ytimg.com/vi/S7OCzDNeENg/maxresdefault.jpg',
                created_at: '2016:11:17 3:24:05'
            },
            {
                name: 'Название манги 2',
                img: 'https://myanimelist.cdn-dena.com/s/common/uploaded_files/1461686209-a6dece26808a56986a5b2a432ab0738f.jpeg',
                created_at: '2016:11:17 3:24:02'
            },
            {
                name: 'Название манги 3',
                img: 'https://i.ytimg.com/vi/WDElrHcEEGY/maxresdefault.jpg',
                created_at: '2016:11:17 3:24:04'
            }
        ],
    },

    methods: {
        byNew() {
        },
        byOld() {
        }
    }
});