<div class="modal-overlay" id="loginModal">
    <div class="modal-container auth-modal">
        <div class="modal-header">
            <h2>Вход в аккаунт</h2>
            <button class="modal-close" onclick="closeLoginModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="form-wrapper">
            <form class="modal-form">
                <input type="hidden" name="formid" value="login">
                @csrf
                <div class="form-group" data-field="email">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="ivan@gmail.com">
                </div>

                <div class="form-group" data-field="password">
                    <label for="password">Пароль <span class="required">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
                </div>

                <div class="form-actions">
                    <input type="submit" value="Войти" class="modal-submit-btn"></input>
                </div>

                <div class="auth-links">
                    <a href="#" onclick="openRegisterModal(); return false;">Нет аккаунта? Зарегистрироваться</a>
                </div>
            </form>
        </div>
    </div>
</div>
