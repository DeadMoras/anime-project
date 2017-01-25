Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

let vm = new Vue({
    el: '#animeCrup',
    data: {
        showLinkBool: false,
        showIconsBool: false,
        justWait: false,
        uploadedVideo: false,
        file: {
            title: '',
            description: '',
            wallpost: 0,
            group_id: null,
            album_id: null,
        }
    },
    methods: {
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

            this.$http.post('/vk-save-video', fd).then((response) => {
                console.log(response);
            });

            this.uploadedVideo = true;
        }
    }
});