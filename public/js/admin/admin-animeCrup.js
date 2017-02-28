Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

let vm = new Vue({
    el: '#animeCrup',
    data: {
        searchSameInput: '',
        sameAnimeStatus: false,
        sameAnimeResponse: [],
        image: '',
        imageUploaded: false,
        showLinkBool: false,
        showIconsBool: false,
        justWait: false,
        uploadedVideo: false,
        showInputLinkVideo: false,
        newVideoLink: '',
        file: {
            title: '',
            description: '',
            wallpost: 0,
            group_id: null,
            album_id: null,
        },
        imageResponse: [],
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
            fd.append('path_to_save_image', 'anime');
            fd.append('image_bundle', 'anime');
            fd.append('image_width', 700);
            fd.append('image_height', 500);

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
        uploadVideo(e) {
            this.justWait = true;
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }

            let fd = new FormData;
            fd.append('video', files[0]);
            fd.append('title', this.file.title);
            fd.append('description', this.file.description);
            fd.append('wallpost', this.file.wallpost);
            fd.append('group_id', this.file.group_id);
            fd.append('album_id', this.file.album_id);

            this.uploadedVideo = true;
            this.$http.post('/vk-save-video', fd).then((response) => {
                if (response.body.success) {
                    let responseVideo = JSON.parse(response.body.success.response);
                    let videoUploadedUrl = 'http://vk.com/video' + response.body.success.vk_user_id + '_' + responseVideo.video_id;

                    Materialize.toast('Video was successfully upload', 3000);
                    this.uploadedVideo = false;
                    this.justWait = false;
                    this.showIconsBool = false;
                    this.showLinkBool = false;
                    this.newVideoLink = videoUploadedUrl;
                    this.showInputLinkVideo = true;
                } else {
                    Materialize.toast('Error', 3000);
                }
            });
        },
        searchSameAnime() {
            if ( this.searchSameInput.length < 1 ) {
                return false;
            }

            this.$http.post('/admin/search/same_anime', {
                text: this.searchSameInput
            }).then((response) => {
                this.sameAnimeStatus = true;
                this.sameAnimeResponse = response.body.data;
            });
        }
    }
});