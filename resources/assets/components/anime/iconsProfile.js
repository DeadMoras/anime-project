import Vue from 'vue';
import axios from 'axios';
import {mapState} from 'vuex';
import {showAlert} from "../../modules/alerts.js";
import {getToken, checkToken} from "../../modules/token.js";

const iconsProfile = Vue.component(
    'icons-profile', {
        props: ['postId'],

        template: `
        <div>
            <a title="Смотрю"
               @click="addToList(0, user.id, postId)">
                <i class="fa fa-eye"
                   aria-hidden="true"></i>
            </a>
            <a title="Буду смотреть"
               @click="addToList(1, user.id, postId)">
                <i class="fa fa-calendar-check-o"
                   aria-hidden="true"></i>
            </a>
            <a title="Просмотрено"
               @click="addToList(2, user.id, postId)">
                <i class="fa fa-eye-slash"
                   aria-hidden="true"></i>
            </a>
            <a title="Любимое"
               @click="addToList(3, user.id, postId)">
                <i class="fa fa-heart"
                   aria-hidden="true"></i>
            </a>
        </div>
    `,
        computed: {
            ...mapState(
                {
                    user: state => state.userStore.user
                }
            )
        },
        methods: {
            addToList(type, user_id, post_id) {
                if (!checkToken()) {
                    showAlert('success-js_button--error', 'Вы должны авторизоваться', 'fa-times');
                    return false;
                }

                axios.post(
                    '/api/user/add-to-list', {
                        type: type,
                        user_id: user_id,
                        post_id: post_id,
                        bundle: 'anime',
                        access_token: getToken()
                    }
                ).then(
                    (response) => showAlert('success-js_button--success', response.data.response, 'fa-check')
                ).catch(
                    (error) => {
                        if (423 == error.response.status) {
                            for (let k in error.response.data.error_data) {
                                showAlert('success-js_button--error', error.response.data.error_data[k], 'fa-times');
                            }
                        } else {
                            showAlert('success-js_button--error', error.response.data.error_data, 'fa-times');
                        }
                    }
                );
            }
        }
    }
);

export default {
    iconsProfile
}