<div class="content-info-box success">
    <h4 class="content-small-title">
        <i class="fas fa-check-circle"></i>
        Информация о заказе #{{ $order_id }}
    </h4>

    <div class="content-grid-2">
        <div class="content-contact-item">
            <div class="content-contact-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="content-contact-info">
                <strong>{{ $order_name }}</strong>
            </div>
        </div>

        <div class="content-contact-item">
            <div class="content-contact-icon">
                <i class="fas fa-phone"></i>
            </div>
            <div class="content-contact-info">
                <a href="tel:{{ $order_phone }}">{{ $order_phone }}</a>
            </div>
        </div>

        <div class="content-contact-item">
            <div class="content-contact-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="content-contact-info">
                <a href="mailto:{{ $order_email }}">{{ $order_email }}</a>
            </div>
        </div>

        <div class="content-contact-item">
            <div class="content-contact-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="content-contact-info">
                <strong>Сумма:</strong> {{ $order_amount }}
                {{ $order_currency }}
            </div>
        </div>
    </div>

    @if (!empty($payment_id))
        <div class="content-divider-light"></div>
        <div class="content-contact-item">
            <div class="content-contact-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="content-contact-info">
                <strong>Платеж #{{ $payment_id }}:</strong>
                {{ $payment_amount }} {{ $order_currency }}
            </div>
        </div>
    @endif
</div>
