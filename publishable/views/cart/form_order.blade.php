<form method="POST" data-commerce-order="{{ $data['form_hash'] }}" class="modal-form" id="orderForm">
    <input type="hidden" name="formid" value="order">
    <h3>Контактные данные</h3>

    <div class="form-group">
        <label for="name">Ваше имя <span class="required">*</span></label>
        <input type="text" id="name" name="name" class="form-control {{ $data['name.errorClass'] ?? '' }} {{ $data['name.requiredClass'] ?? '' }}" value="{{ $data['name.value'] ?? '' }}"
            placeholder="Иван Иванов" required>
        {!! $data['name.error'] ?? '' !!}
    </div>

    <div class="form-group">
        <label for="email">Email <span class="required">*</span></label>
        <input type="email" id="email" name="email" class="form-control {{ $data['email.errorClass'] ?? '' }} {{ $data['email.requiredClass'] ?? '' }}" value="{{ $data['email.value'] ?? '' }}"
            placeholder="ivan@example.com" required>
        {!! $data['email.error'] ?? '' !!}
    </div>

    <div class="form-group">
        <label for="phone">Телефон <span class="required">*</span></label>
        <input type="tel" id="phone" name="phone" class="form-control {{ $data['phone.errorClass'] ?? '' }} {{ $data['phone.requiredClass'] ?? '' }}" value="{{ $data['phone.value'] ?? '' }}"
            placeholder="+7 (999) 123-45-67" required>
        {!! $data['phone.error'] ?? '' !!}
    </div>

    <div data-commerce-deliveries>
        {!! $data['delivery'] !!}
    </div>

    <div data-commerce-payments>
        {!! $data['payments'] !!}
    </div>

    <!-- Комментарий -->
    <div class="form-group">
        <label for="comment">Комментарий к заказу</label>
        <textarea id="comment" name="comment" class="form-control {{ $data['comment.errorClass'] ?? '' }} {{ $data['comment.requiredClass'] ?? '' }}" rows="2"
            placeholder="Дополнительная информация"></textarea>
        {!! $data['comment.error'] ?? '' !!}
    </div>

    <!-- Согласие -->
    <div class="form-check">
        <input type="checkbox" id="agreement" name="agreement" class="form-control {{ $data['agreement.errorClass'] ?? '' }} {{ $data['agreement.requiredClass'] ?? '' }}" checked required>
        <label for="agreement">Я согласен на обработку персональных данных</label>
        {!! $data['agreement.error'] ?? '' !!}
    </div>
     {!! $data['form.messages'] ?? '' !!}
    <!-- Кнопка отправки -->
    <button type="submit" class="modal-submit-btn" id="submitBtn">
        <span>Подтвердить заказ</span>
    </button>
</form>