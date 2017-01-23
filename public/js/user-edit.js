var vm = new Vue({
    el: '#userEdit',
    data: {
        backgroundImage: 'https://pp.vk.me/c604318/v604318903/44c22/XKw6IdnZ_xk.jpg'
    },
    methods: {
        uploadImage( e ) {
            var files = e.target.files || e.dataTransfer.files;
            if( !files.length ) {
                return;
            }
            this.createImage( files[0] );
        },
        createImage( file ) {
            var image = new Image();
            var reader = new FileReader();
            var vm = this;

            reader.onload = ( e ) =>
            {
                vm.backgroundImage = e.target.result;
            };
            reader.readAsDataURL( file );
        },
        removeImage: function( e )
        {
            this.backgroundImage = '';
        },
    }
});