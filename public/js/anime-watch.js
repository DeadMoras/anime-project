Vue.component('icons-profile', {
    props: ['name'],

    template: `
        <div>
            <a title="Смотрю">
                <i class="fa fa-eye"
                   aria-hidden="true"></i>
            </a>
            <a title="Буду смотреть">
                <i class="fa fa-calendar-check-o"
                   aria-hidden="true"></i>
            </a>
            <a title="Просмотрено">
                <i class="fa fa-eye-slash"
                   aria-hidden="true"></i>
            </a>
            <a title="Любимое">
                <i class="fa fa-heart"
                   aria-hidden="true"></i>
            </a>
        </div>
    `,
});

var vm = new Vue({
    el: '#watch-anime.php',
    data: {
        videoSrc: '',
        openSameAnime: false,
        descriptionToAnime: false,
        videos: [
            {
                id: '1',
                src: 'http://iandevlin.github.io/mdn/video-player/video/tears-of-steel-battle-clip-medium.mp4',
            },
            {
                id: '2',
                src: 'https://drive.google.com/file/d/0BxmILbfb0npicVhvYnJmZk81dFk/preview',
            },
            {
                id: '3',
                src: 'http://iandevlin.github.io/mdn/video-player/video/tears-of-steel-battle-clip-medium.mp4',
            },
            {
                id: '4',
                src: 'http://iandevlin.github.io/mdn/video-player/video/tears-of-steel-battle-clip-medium.mp4',
            },
        ]
    },
    methods: {
        watchAnime: function(id) {
            for(var k of this.videos) {
                this.$set(k, 'active', false);
                if( k.active == true ) {
                    this.videoSrc = '';
                    k.active = false;
                }

                if( k.id == id ) {
                    this.videoSrc = k.src;
                    this.$set(k, 'active', true);
                }
            }
        }
    }
});