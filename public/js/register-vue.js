Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

Vue.component('social-icon', {
    template: `
            <i aria-hidden="true"></i>
    `,

    data() {
        return {}
    }
});

Vue.filter('minLength', function (value, nameInput, min, max) {
    vm.error[nameInput] = {};

    if (value.length < min) {
        vm.error[nameInput].min = 'Поле ' + nameInput + ' не может содержать менее ' + min + ' символов';
        vm.disabledButton = true;
    } else if (value.length > max) {
        vm.error[nameInput].max = 'Поле ' + nameInput + ' не может содержать более ' + max + ' символов';
        vm.disabledButton = true;
    } else if (value.length > min && value.length < max) {
        if (vm.error !== undefined) {
            delete vm.error[nameInput];
            if (!vm.error.email && !vm.error.login && !vm.error.password) {
                vm.disabledButton = false;
            }
        }
    }
});

var vm = new Vue({
    el: '#register-form',
    data: {
        image: '',
        imageUploaded: false,
        user: {
            login: '',
            email: '',
            password: '',
            passwordConfirmation: '',
            login: '',
            sex: false,
            registerVk: '',
            registerFacebook: '',
            registerTwitter: '',
            registerSkype: '',
            imageId: null,
            imageName: '',
            imageType: '',
        },
        errorPasswordRepeat: false,
        error: {
            email: {
                required: 'Поле email обязательно для заполнения'
            },
            login: {
                required: 'Поле login обязательно для заполнения'
            },
            password: {
                required: 'Поле password обязательно для заполнения'
            },
        },
        disabledButton: true,
        justWait: false,
        socialIcons: {
            registerVk: false,
            registerSkype: false,
            registerTwitter: false,
            registerFacebook: false,
        }
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
            fd.append('path_to_save_image', 'user');
            fd.append('image_bundle', 'user');
            fd.append('image_width', 500);
            fd.append('image_height', 500);

            this.$http.post('/save_image', fd).then((response) => {
                vm.user.imageId = response.body.image.id;
                vm.user.imageName = response.body.image.name;
                vm.user.imageType = response.body.image.mimetype;
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
        checkRePassword() {
            if (this.user.password && this.user.password !== this.user.passwordConfirmation) {
                this.errorPasswordRepeat = true;
                this.error.passwordError = 'Пароли не совпадают';
                this.disabledButton = true;
            } else {
                if (!this.error.email && !this.error.login && !this.error.password) {
                    this.disabledButton = false;
                }
                this.errorPasswordRepeat = false;
                delete this.error.passwordError;
            }
        },
        justLength(value, nameInput, min, max) {
            this.$options.filters.minLength(value, nameInput, min, max);
        },
        justRegister() {
            this.$http.post('/register', {
                user: this.user,
                imageUploaded: this.imageUploaded,
            }).then((response) => {
                if (typeof response.body == "object") {
                    let responseServer = response.body;
                    for (let k in responseServer) {
                        Materialize.toast(responseServer[k].toString(), 3000);
                    }
                } else {
                    Materialize.toast(response.body.success, 2000);
                }
            }, (response) => {
                console.log(JSON.stringify(response));
            });
        },
        socialInput(iconName) {
            if (!this.socialIcons.iconName) {
                if (this.socialIcons[iconName] == false) {
                    this.socialIcons[iconName] = true;
                } else {
                    this.socialIcons[iconName] = false;
                }
            }
        },
        socialIconsFor(iconName) {
            if (this.socialIcons[iconName] == true) {
                return true;
            } else {
                return false;
            }
        }
    },
    computed: {
        errorButton: function () {
            return {
                'col s12 btn': 'btn',
                'disabled': this.disabledButton,
            }
        }
    }
})