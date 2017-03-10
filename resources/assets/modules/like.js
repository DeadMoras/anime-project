import {checkToken, getToken} from './token.js';
import axios from 'axios';
let Promise = require('bluebird');

export function likePost(post_id, bundle, user_id) {
    return new Promise(
        function (resolve, reject) {
            if (!user_id) {
                alertify.notify('Вы должны авторизоваться', 'error', 3)
                return false;
            }

            if (2 > bundle.length) {
                return false;
            }

            if (!checkToken()) {
                alertify.notify('Вы должны авторизоваться', 'error', 3)
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
                            alertify.notify(error.response.data.error_data[k], 'error', 2);
                        }
                    } else {
                        alertify.notify(error.response.data.error_data, 'error', 3);
                    }
                }
            );
        }
    )
}
