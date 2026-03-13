@extends('layout.app')

@section('styles')
    <style>
        /* Заголовок корзины */
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0 40px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .cart-header h1 {
            font-size: 2.5rem;
            color: var(--deep-green);
        }

        .cart-header-actions {
            display: flex;
            gap: 16px;
        }

        .btn-clear {
            background: transparent;
            border: 1px solid #ef4444;
            color: #ef4444;
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-clear:hover {
            background: #ef4444;
            color: white;
        }

        /* Основная раскладка корзины */
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
            margin-bottom: 60px;
        }

        /* Список товаров */
        .cart-items {
            background: var(--white);
            border-radius: 32px;
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 24px;
            padding: 24px;
            border-bottom: 1px solid var(--border-light);
            position: relative;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            width: 100px;
            height: 100px;
            background: var(--sage);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--deep-green);
        }

        .cart-item-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .cart-item-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--deep-green);
            text-decoration: none;
        }

        .cart-item-title:hover {
            color: var(--fresh-green);
        }

        .cart-item-category {
            color: var(--light-graphite);
            font-size: 0.9rem;
        }

        .cart-item-spec {
            color: var(--light-graphite);
            font-size: 0.9rem;
            display: flex;
            gap: 16px;
        }

        .cart-item-spec span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .cart-item-spec i {
            color: var(--fresh-green);
            font-size: 0.8rem;
        }

        .cart-item-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 16px;
        }

        .cart-item-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--deep-green);
            white-space: nowrap;
        }

        .cart-item-old-price {
            font-size: 1rem;
            color: var(--light-graphite);
            text-decoration: line-through;
            margin-left: 8px;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            border: 1px solid var(--border-light);
            border-radius: 40px;
            overflow: hidden;
        }

        .cart-item-remove {
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.2s;
            font-size: 1.1rem;
            background: transparent;
            border: none;
        }

        .cart-item-remove:hover {
            color: #ef4444;
        }

        /* Боковая панель - оформление */
        .cart-sidebar {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .cart-summary {
            background: var(--white);
            border-radius: 32px;
            border: 1px solid var(--border-light);
            padding: 28px;
        }

        .cart-summary h3 {
            font-size: 1.3rem;
            color: var(--deep-green);
            margin-bottom: 24px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            color: var(--light-graphite);
        }

        .summary-row.total {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--deep-green);
            border-top: 1px solid var(--border-light);
            padding-top: 16px;
            margin-top: 16px;
        }

        .summary-row span:last-child {
            font-weight: 600;
            color: var(--graphite);
        }

        .summary-row.total span:last-child {
            color: var(--deep-green);
            font-size: 1.5rem;
        }

        .btn-checkout {
            width: 100%;
            background: var(--deep-green);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .btn-checkout:hover {
            background: var(--fresh-green);
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }

        /* Пустая корзина */
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
            background: var(--white);
            border-radius: 48px;
            border: 1px solid var(--border-light);
            margin: 40px 0;
        }

        .empty-cart i {
            font-size: 6rem;
            color: var(--border-light);
            margin-bottom: 24px;
        }

        .empty-cart h2 {
            font-size: 2rem;
            color: var(--deep-green);
            margin-bottom: 16px;
        }

        .empty-cart p {
            color: var(--light-graphite);
            margin-bottom: 32px;
        }

        .empty-cart .btn-primary {
            display: inline-flex;
        }

        .cart-item-options {
            margin: 8px 0;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .cart-item-options .options-label {
            font-weight: 600;
            color: #495057;
            display: block;
            margin-bottom: 4px;
        }

        .cart-item-options .options-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .cart-item-options .option-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .cart-item-options .option-item:last-child {
            border-bottom: none;
        }

        .cart-item-options .option-price {
            color: #28a745;
            font-weight: 500;
            margin-left: 8px;
        }

        /* Адаптация */
        @media (max-width: 1024px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }

            .cart-sidebar {
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .cart-item {
                grid-template-columns: 80px 1fr;
            }

            .cart-item-actions {
                grid-column: span 2;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                margin-top: 16px;
            }

            .cart-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .cart-header h1 {
                font-size: 2rem;
            }

            .cart-item-price {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 480px) {
            .cart-item {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .cart-item-image {
                width: 100%;
                height: 140px;
            }

            .cart-item-actions {
                grid-column: span 1;
                flex-wrap: wrap;
            }

            .cart-header-actions {
                width: 100%;
                flex-direction: column;
            }

            .promo-input {
                flex-direction: column;
            }
        }

        /* Стили для опций в корзине */
        .cart-item-options {
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .options-label {
            font-weight: 600;
            color: #495057;
            display: block;
            margin-bottom: 8px;
            font-size: 0.95em;
        }

        .options-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .option-item {
            display: flex;
            align-items: center;
            padding: 6px 8px;
            margin: 4px 0;
            background: white;
            border-radius: 4px;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .option-item:hover {
            background: #fff3cd;
            border-color: #ffeeba;
        }

        .option-name {
            flex: 1;
            font-size: 0.9em;
        }

        .option-price {
            color: #28a745;
            font-weight: 500;
            margin: 0 10px;
            font-size: 0.9em;
        }

        .remove-option-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
            opacity: 0.7;
        }

        .remove-option-btn:hover {
            opacity: 1;
            background: #dc3545;
            color: white;
        }

        .remove-option-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .remove-option-btn.loading {
            position: relative;
            color: transparent;
        }

        .remove-option-btn.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-top: -8px;
            margin-left: -8px;
            border: 2px solid #dc3545;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .price-breakdown {
            display: block;
            font-size: 0.8em;
            color: #6c757d;
            margin-top: 4px;
        }

        .cart-item-price {
            font-weight: 600;
            color: #28a745;
        }

        .order-form {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .order-summary {
            background: #f8f9fa;
            padding: 24px;
            border-bottom: 1px solid #e9ecef;
        }

        .order-summary h3 {
            margin: 0 0 20px 0;
            color: #333;
            font-size: 1.3rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            color: #666;
        }

        .summary-row.total {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid #dee2e6;
            font-weight: 700;
            color: #333;
            font-size: 1.2rem;
        }

        .order-form-fields {
            padding: 24px;
        }

        .order-form-fields h3 {
            margin: 24px 0 16px 0;
            color: #333;
            font-size: 1.2rem;
        }

        .order-form-fields h3:first-of-type {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
            font-weight: 500;
        }

        .required {
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 4px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Стили для модального окна */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow-y: auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .modal-overlay.active {
            display: block;
        }

        .modal-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid var(--border-light);
            background: var(--deep-green);
            color: white;
            border-radius: 24px 24px 0 0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: white;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0 8px;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .modal-close:hover {
            opacity: 1;
        }

        .modal-form {
            padding: 24px;
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Мини-сумма заказа в модалке */
        .order-mini-summary {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .order-mini-summary .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            color: var(--light-graphite);
        }

        .order-mini-summary .summary-row.total {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid var(--border-light);
            font-weight: 600;
            color: var(--deep-green);
            font-size: 1.1rem;
        }

        /* Адаптация существующих стилей для модалки */
        .modal-form h3 {
            margin: 20px 0 12px 0;
            color: var(--deep-green);
            font-size: 1.1rem;
        }

        .modal-form .form-group {
            margin-bottom: 16px;
        }

        .modal-form .delivery-item,
        .modal-form .payment-item {
            padding: 12px;
        }

        .modal-submit-btn {
            width: 100%;
            padding: 16px;
            background: var(--deep-green);
            color: white;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 16px;
        }

        .modal-submit-btn:hover {
            background: var(--fresh-green);
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }

        .modal-submit-btn:disabled {
            background: #ccc;
            pointer-events: none;
        }

        /* Стили для loading состояния */
        .modal-submit-btn.loading {
            position: relative;
            color: transparent;
        }

        .modal-submit-btn.loading::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            top: 50%;
            left: 50%;
            margin: -12px 0 0 -12px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Адаптация для мобильных */
        @media (max-width: 768px) {
            .modal-container {
                margin: 20px auto;
            }

            .modal-header {
                padding: 16px;
            }

            .modal-form {
                padding: 16px;
            }

            .modal-header h2 {
                font-size: 1.2rem;
            }
        }

        /* Блокировка прокрутки страницы при открытой модалке */
        body.modal-open {
            overflow: hidden;
        }

        /* Стили для доставки и оплаты */
        .delivery-options,
        .payment-options {
            margin-bottom: 24px;
        }

        .delivery-item,
        .payment-item {
            display: block;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .delivery-item:hover,
        .payment-item:hover {
            border-color: #28a745;
            background: #f8fff9;
        }

        .delivery-item input[type="radio"],
        .payment-item input[type="radio"] {
            margin-right: 12px;
        }

        .delivery-info,
        .payment-info {
            display: inline-flex;
            align-items: center;
            width: calc(100% - 30px);
        }

        .delivery-name,
        .payment-name {
            flex: 1;
            font-weight: 500;
            color: #333;
        }

        .delivery-price {
            font-weight: 600;
            color: #28a745;
            margin-left: 12px;
        }

        .delivery-desc,
        .payment-desc {
            display: block;
            margin-top: 8px;
            margin-left: 30px;
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* Кнопка отправки */
        .submit-btn {
            width: 100%;
            padding: 16px 24px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin: 24px 0 16px;
        }

        .submit-btn:hover {
            background: #218838;
        }

        .submit-btn:active {
            transform: translateY(1px);
        }

        .submit-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        /* Чекбокс согласия */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .form-check label {
            color: #6c757d;
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* Анимация загрузки */
        .submit-btn.loading {
            position: relative;
            color: transparent;
            pointer-events: none;
        }

        .submit-btn.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin: -10px 0 0 -10px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .order-form {
                margin: 0 15px;
            }

            .delivery-info,
            .payment-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .delivery-price {
                margin-left: 0;
                margin-top: 4px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Заголовок корзины -->
        <div class="cart-header">
            <h1>{{ $pagetitle }}</h1>
            <div class="cart-header-actions">
                <button class="btn-clear" data-commerce-action="clean"><i class="fas fa-trash-alt"></i> Очистить
                    корзину</button>
            </div>
        </div>
        {!! $cart !!}
        <div class="modal-overlay" id="orderModal">
            <div class="modal-container">
                <!-- Заголовок модалки -->
                <div class="modal-header">
                    <h2>Оформление заказа</h2>
                    <button class="modal-close" onclick="closeOrderModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                {!!$order!!}
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        // Функции открытия/закрытия модалки
        function openOrderModal() {
            document.getElementById('orderModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('orderForm');
            const submitBtn = document.getElementById('submitBtn');
            const phoneInput = document.getElementById('phone');

            // Маска для телефона
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');

                    if (value.length === 0) {
                        e.target.value = '';
                        return;
                    }

                    // Для России начинаем с +7
                    if (value.length === 1) {
                        value = '+7';
                    } else if (value.length <= 4) {
                        value = '+7 (' + value.substring(1, 4);
                    } else if (value.length <= 7) {
                        value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7);
                    } else if (value.length <= 9) {
                        value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7) + '-' + value.substring(7, 9);
                    } else {
                        value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7) + '-' + value.substring(7, 9) + '-' + value.substring(9, 11);
                    }

                    e.target.value = value;
                });

                // Устанавливаем курсор в конец при фокусе
                phoneInput.addEventListener('focus', function(e) {
                    const len = e.target.value.length;
                    e.target.setSelectionRange(len, len);
                });
            }

            // Валидация формы перед отправкой
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (submitBtn.disabled) {
                        e.preventDefault();
                        return;
                    }

                    const name = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const phone = document.getElementById('phone').value.trim();
                    const agreement = document.getElementById('agreement').checked;

                    let errors = [];

                    if (!name) {
                        errors.push('Введите имя');
                    }

                    if (!email) {
                        errors.push('Введите email');
                    } else if (!isValidEmail(email)) {
                        errors.push('Введите корректный email');
                    }

                    if (!phone) {
                        errors.push('Введите телефон');
                    } else if (phone.replace(/\D/g, '').length < 11) {
                        errors.push('Введите корректный телефон');
                    }

                    if (!agreement) {
                        errors.push('Необходимо согласие на обработку данных');
                    }

                    if (errors.length > 0) {
                        e.preventDefault();
                        alert(errors.join('\n'));
                        return;
                    }

                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                });
            }

            // Закрытие по клику на оверлей
            document.getElementById('orderModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeOrderModal();
                }
            });

            // Закрытие по ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeOrderModal();
                }
            });

            // Вспомогательная функция для проверки email
            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }
        });
    </script>
@endsection
