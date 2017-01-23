let vm = new Vue({
    el: '#read-tom',
    data: {
        items: [
            {
                id: '1',
                image: 'http://risens.team/manga/sds/201_2/01.png',
            },
            {
                id: '2',
                image: 'http://risens.team/manga/sds/201_2/02.png',
            },
            {
                id: '3',
                image: 'http://risens.team/manga/sds/201_2/03.png',
            },
            {
                id: '4',
                image: 'http://risens.team/manga/sds/201_2/04.png',
            },
            {
                id: '5',
                image: 'http://risens.team/manga/sds/201_2/05.png',
            },
        ],
        currentImage: 0,
    },
    mounted: function() {
        this.checkPageId();
    },
    computed: {
        isActive() {
            for(k of this.items) {
                if( k.active == true ) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    },
    methods: {
        nextImage() {
            if( (this.currentImage + 1) < this.items.length ) {
                this.$set(this.items[this.currentImage], 'active', false);
                this.currentImage++;
                this.$set(this.items[this.currentImage], 'active', true);
            }
        },
        oldImage() {
            if( this.currentImage != 0 && this.currentImage < this.items.length ) {
                this.$set(this.items[this.currentImage], 'active', false);
                this.currentImage--;
                this.$set(this.items[this.currentImage], 'active', true);
            }
        },
        checkPageId() {
            for(k of this.items) {
                if( k.id == '1' ) {
                    this.$set(k, 'active', true);
                } else {
                    this.$set(k, 'active', false);
                }
            }
        },
        changeActive(id, index) {
            this.currentImage = index;
            for(k of this.items) {
                if( k.id == id ) {
                    if( k.active == true ) {
                        return false;
                    } else {
                        k.active = true;
                    }
                } else {
                    k.active = false;
                }
            }
        },
    },
});

document.onkeydown = function(e) {
    e = e || window.event;

    if( e.keyCode == 37 ) {
        vm.oldImage.call();
    }

    if( e.keyCode == 39 ) {
        vm.nextImage.call();
    }
}
