<template lang="pug">
    div
        div(v-if="user == false")
            <auth-modal></auth-modal>
        div.container
            div.row
                div.col.l6.s12
                    h5.white-text Footer Content
                    p.grey-text.text-lighten-4 You can use rows and columns here to organize your footer content.
                div.col.l4.offset-l2.s12
                    h5.white-text Links
                    ul
                        li
                            a.grey-text.text-lighten-3(href="#!") Link 1
                        li
                            a.grey-text.text-lighten-3(href="#!") Link 2
                        li
                            a.grey-text.text-lighten-3(href="#!") Link 3
                        li
                            a.grey-text.text-lighten-3(href="#!") Link 4
        div.footer-copyright
            div.container
                span © 2014 Copyright Text
                a.grey-text.text-lighten-4.right(href="#!") More Links
</template>

<script>
    import authModal from '../../components/auth/authModal.vue';
    import store from '../../store/store.js';
    import {mapState} from 'vuex';

    export default {
        components: {
            authModal
        },
        data() {
            return {}
        },
        computed: {
            ...mapState(
                {
                    user: state => state.userStore.user
                }
            )
        }
    }
</script>