<div class="modal-overlay" id="registerModal">
    <div class="modal-container auth-modal">
        <div class="modal-header">
            <h2>Регистрация</h2>
            <button class="modal-close" onclick="closeRegisterModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="form-wrapper">
            <form class="modal-form">
                <input type="hidden" name="formid" value="register">
                @csrf 
                <div class="form-group" data-field="username">
                    <label for="username">Имя пользователя <span class="required">*</span></label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="ivancom">
                </div>

                <div class="form-group" data-field="email">
                    <label for="register_email">Email <span class="required">*</span></label>
                    <input type="email" name="email" id="register_email" class="form-control" placeholder="ivan@example.com">
                </div>

                <div class="form-group" data-field="password">
                    <label for="password">Пароль <span class="required">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
                </div>

                <div class="form-group" data-field="repeatPassword">
                    <label for="repeatPassword">Подтверждение пароля <span class="required">*</span></label>
                    <input type="password" name="repeatPassword" id="repeatPassword" class="form-control" placeholder="••••••••">
                </div>

                <div class="form-actions">
                    <input type="submit" value="Зарегистрироваться" class="modal-submit-btn">
                </div>
                <div class="auth-links">
                    <a href="#" onclick="openLoginModal(); return false;">Уже есть аккаунт? Войти</a>
                </div>
            </form>
        </div>
    </div>
</div>
