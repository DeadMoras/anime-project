let search = new Vue({
    el: '#searchVue',
    data: {
        searchInput: '',
        bundle: '',
    },
    methods: {
        justSearch() {
            this.bundle = vm.$data.searchBundle;
            this.$http.post('/admin/search', {
                bundle: this.bundle,
                search: this.searchInput
            }).then((response) => {
                vm.$data.searchStatus = true;
                vm.$data.searchData = response.body.data;
            });
        }
    }
});
