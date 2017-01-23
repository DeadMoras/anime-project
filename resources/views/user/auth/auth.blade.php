<div id="mainAuth">
    <div id="auth-modal"
     class="modal modal-auth">
    <div class="modal-content modal-content_auth">
        <div class="header">
            <h5>Авторизация</h5>
            <span>
				<i class="fa fa-times modal-close"
                   aria-hidden="true"></i>
			</span>
        </div>
        <div class="inputs">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text"
                           name="login"
                           id="auth-login_modal"
                           v-model="email">
                    <label for="auth-login_modal">Логин</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="password"
                           name="password"
                           id="auth-password_modal"
                           v-model="password">
                    <label for="auth-password_modal">Пароль</label>
                </div>
            </div>
            <div class="row button">
                <div class="input-field col s12">
                    <a href="#"
                       class="button-auth waves-effect waves-light btn col s12"
                       @click="register()">Авторизоваться</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>