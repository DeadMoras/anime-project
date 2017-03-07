import {checkToken, getToken} from './token.js';
import axios from 'axios';
import {showAlert} from './alerts.js';
var Promise = require('bluebird');

export function likePost(post_id, bundle, user_id) {
    return new Promise(
        function (resolve, reject) {
            if (!user_id) {
                showAlert('success-js_button--error', 'Вы должны авторизоваться', 'fa-times')
                return false;
            }

            if (2 > bundle.length) {
                return false;
            }

            if (!checkToken()) {
                showAlert('success-js_button--error', 'Вы должны авторизоваться', 'fa-times')
                return false;
            }

            if (!post_id) {
                return false;
            }

            axios.post(
                '/api/likes/set', {
                    user_id: user_id,
                    post_id: post_id,
                    bundle: bundle,
                    access_token: getToken()
                }
            ).then(
                (response) => {
                    return resolve(true);
                }
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
    )
}
