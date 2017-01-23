Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value')

let auth = new Vue({
    el: '#mainAuth',
    data: {
        email: '',
        password: '',
    },
    methods: {
        register() {
            if( this.email.length > 1 && this.password.length > 1 ) {
                this.$http.post('/auth', {
                    email: this.email,
                    password: this.password
                }).then((response) => {
                    if ( response.body.error ) {
                        Materialize.toast(JSON.stringify(response.body.error), 3000);
                    } else if ( response.body.success ) {
                        location.reload();
                    } else {
                        let responseServer = response.body;
                        for ( let k in responseServer ) {
                            Materialize.toast(responseServer[k].toString(), 3000);
                        }
                    }
                });
            }
        }
    }
});