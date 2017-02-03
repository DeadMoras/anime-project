Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

let vm = new Vue({
    el: '#mangaCrup',
    data: {
        image: '',
        imageUploaded: false,
        showLinkBool: false,
        showIconsBool: false,
        justWait: false,
    },
    methods: {
        uploadImage(e) {
            this.justWait = true;
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }

            let fd = new FormData;
            fd.append('image', files[0]);
            fd.append('path_to_save_image', 'anime');
            fd.append('image_bundle', 'anime');
            fd.append('image_width', 600);
            fd.append('image_height', 600);

            this.$http.post('/save_image', fd).then((response) => {
                vm.imageResponse.imageId = response.body.image.id;
                vm.imageResponse.imageName = response.body.image.name;
                vm.imageResponse.imageType = response.body.image.mimetype;
            });

            this.imageUploaded = true;

            this.createImage(files[0]);
        },
        createImage(file) {
            let image = new Image();
            let reader = new FileReader();
            let vm = this;
            this.justWait = false;

            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        removeImage(e) {
            this.image = '';
            this.imageUploaded = false;
        },
        vkImages(e) {
            let files = e.target.files;
            let fd = new FormData;

            for ( let k of files ) {
                fd.append(k.name, k);
            }

            this.$http.post('/vk-save-image', fd).then((response) => {
                console.log(response.body);
            });
        }
    }
});