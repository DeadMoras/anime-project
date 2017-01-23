let vm = new Vue({
  el: '#music',
  data: {
    items: [
      { id: 1,name: 'Music name 1', time: '3:43', src: 'http://best-muzon.me/dl/online/YUvFUPpsYHR-AFAN7LnO6A/1483944099/songs12/2016/12/dj-snake-feat.-justin-bieber-let-me-love-you-sean-(best-muzon.me).mp3' },
      { id: 2,name: 'Music name 2', time: '2:13', src: 'http://best-muzon.me/dl/online/9tK5EX-GMvcUlEz_imvgoQ/1483944099/songs12/2016/10/bahh-tee-nikotin-dj-karp-remix-(best-muzon.me).mp3' },
      { id: 3,name: 'Music name 3', time: '4:01', src: 'http://best-muzon.me/dl/online/vqjdGefLUO0aLg5rWFG3Gw/1483944099/songs12/2016/10/ruki-vverkh-kogda-my-byli-molodymi-(best-muzon.me).mp3' },
      { id: 4,name: 'Music name 4', time: '1:05', src: 'http://best-muzon.me/dl/online/9ZvaOHCzphAn3KajhOa7WA/1483944099/songs12/2016/11/matthew-koma-kisses-back-(best-muzon.me).mp3' },
    ],
    aud: null,
    activeItem: {  },
    activeItemStatus: false,
    musicPlay: 0,
    musicVolume: 50,
  },
  computed: {
    musicVolumeChange () {
      if ( null != this.aud ) {
        if ( this.musicVolume == 0 ) {
          this.aud.volume = 0;
        } else if ( this.musicVolume <= 10 ) { 
          this.aud.volume = 0.1;
        } else if ( this.musicVolume > 10 && this.musicVolume <= 20 ) {
          this.aud.volume = 0.2;
        } else if ( this.musicVolume > 20 && this.musicVolume <= 30 ) {
          this.aud.volume = 0.3;
        } else if ( this.musicVolume > 30 && this.musicVolume <= 40 ) {
          this.aud.volume = 0.4;
        } else if ( this.musicVolume > 40 && this.musicVolume <= 50 ) {
          this.aud.volume = 0.5;
        } else if ( this.musicVolume > 50 && this.musicVolume <= 60 ) {
          this.aud.volume = 0.6;
        } else if ( this.musicVolume > 60 && this.musicVolume <= 70 ) {
          this.aud.volume = 0.7;
        } else if ( this.musicVolume > 70 && this.musicVolume <= 80 ) {
          this.aud.volume = 0.8;
        } else if ( this.musicVolume > 80 && this.musicVolume <= 90 ) {
          this.aud.volume = 0.9;
        } else if ( this.musicVolume > 90 && this.musicVolume <= 100 ) {
          this.aud.volume = 1;
        } 
      }
    }
  },
  methods: {
    musicOn(id, src) {
      if ( this.activeItemStatus == true && this.musicPlay == id ) {
        this.$nextTick(() => this.activeItemStatus = false);
        this.aud.pause();
      }

      if ( this.aud == null ) {
        this.aud = new Audio(src);
      }

      if ( this.musicPlay != id ) {
        this.aud.currentTime = 0;
        this.aud.src = src;
      }

      for ( k of this.items ) {      
        if ( k.id == id ) {
          if ( this.activeItemStatus == false && this.musicPlay == id ) {
            this.activeItemStatus = true;
            this.aud.play();
          } else if ( this.musicPlay == 0 ) {
            this.aud.currentTime = 0;
            this.aud.src = src;
            this.activeItemStatus = true;
            this.aud.play();
          } else if ( this.musicPlay != id ) {
            this.aud = null;
            this.activeItemStatus = true;
            this.aud = new Audio(k.src);
            this.aud.play();
          }

          this.musicPlay = id;
          this.activeItem.id = id;
          this.activeItem.src = src;
          this.activeItem.name = k.name;
        }
      }
    },
    prevMusic(id, src) {
      if ( id == 1 ) {
        return false;
      } else {
        this.musicOn(--id, src);
      }
    },
    nextMusic(id, src) {
      if ( this.items.length < ++id ) {
        return false;
      } else {
        this.musicOn(id++, src);
      }
    },
    byNew() {

    },
    byOld() {

    }
  }
});