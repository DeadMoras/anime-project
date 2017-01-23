var vm = new Vue({
    el: '#userIdProfile',
    data: {
        userAvatar: 'https://pp.vk.me/c604318/v604318852/414ed/5Q1nNjzETV8.jpg',
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
                vm.userAvatar = e.target.result;
            };
            reader.readAsDataURL( file );
        },
        removeImage: function( e )
        {
            this.userAvatar = '';
        },
    }
});