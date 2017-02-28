Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

let vm = new Vue({
    el: '#mangaCrup',
    data: {
        image: '',
        imageUploaded: false,
        showLinkBool: false,
        showIconsBool: false,
        justWait: false,
        vkUploadImageMethod: '',
        vkUploadImageShowForm: false,
        vkAlbum: {
            albumId: null,
            groupId: null
        },
        vkUploadedImageId: {
            id: 0,
            src: '',
            status: false
        },
        imageResponse: [],
        imagesToTom: [],
        vkUploadedImageToms: [],
        vkUploadedImagesToToms: 0,
    },
    methods: {
        uploadImage(e) {
            this.justWait = true;
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }

            let fd = new FormData;
            fd.append('image['+ files[0].name +']', files[0]);
            fd.append('path_to_save_image', 'manga');
            fd.append('image_bundle', 'manga');
            fd.append('image_width', 600);
            fd.append('image_height', 600);

            this.$http.post('/save_image', fd).then((response) => {
                for ( let k of response.data.image ) {
                    let newObject = {
                        imageId: k.id,
                        imageName: k.name,
                        imageType: k.mimetype
                    };
                    this.imageResponse.push(newObject);
                }
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
        uploadVkImages(method) {
            this.vkUploadImageMethod = method;
            this.vkUploadImageShowForm = true;
        },
        vkImages(e) {
            this.justWait = true;
            let files = e.target.files;
            let fd = new FormData;
            fd.append('method', this.vkUploadImageMethod);
            if ( this.vkUploadImageMethod == 'albumUpload' ) {
                fd.append('vkId[albumId]', this.vkAlbum.albumId);
                fd.append('vkId[groupId]', this.vkAlbum.groupId);
            } else if ( this.vkUploadImageMethod == 'wallUpload' ) {
                fd.append('vkId[groupId]', this.vkWall.groupId);
            }

            for ( let k of files ) {
                fd.append('images[]', k);
            }

            this.$http.post('/vk-save-image', fd).then((response) => {
                this.vkUploadImageShowForm = false;
                this.justWait = false;
                for ( let k in response.body.success.response ) {
                    this.vkUploadedImageId.id = response.body.success.response[k].id;
                    this.vkUploadedImageId.src = response.body.success.response[k].src;
                    this.vkUploadedImageId.status = true;
                }
            });
        },
        deleteTom() {
            this.vkUploadedImageId = {
                id: 0,
                src: '',
                status: false
            };
        },
        addToTom(id) {
            this.vkUploadedImagesToToms = id;
            this.$http.post('/admin/to-toms', {
                tomId: id
            }).then((response) => {
                if ( response.body.success ) {
                    for ( let k in response.body.success ) {
                        let newObject = {
                            id: response.body.success[k].each_tom_id,
                            src: response.body.success[k].src,
                            vk_images_id: response.body.success[k].vk_images_id
                        };

                        this.imagesToTom.push(newObject);
                    }
                }
            });
        },
        uploadedToms() {

        },
        uploadImagesToTom(e) {
            this.justWait = true;
            let files = e.target.files;
            let fd = new FormData;
            fd.append('method', 'albumUpload');
            fd.append('vkId[albumId]', this.vkAlbum.albumId);
            fd.append('vkId[groupId]', this.vkAlbum.groupId);

            for ( let k of files ) {
                fd.append('images[]', k);
            }

            this.$http.post('/vk-save-image', fd).then((response) => {
                this.vkUploadImageShowForm = false;
                this.justWait = false;
                for ( let k in response.body.success.response ) {
                    let newObject = {
                        id: response.body.success.response[k].id,
                        src: response.body.success.response[k].src,
                    };

                    this.vkUploadedImageToms.push(newObject);
                }
            });
        }
    }
});