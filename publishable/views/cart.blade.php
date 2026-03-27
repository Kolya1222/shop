@extends('layout.app')

@section('styles')
    <style>
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

        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
            margin-bottom: 60px;
        }

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
        }

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
        {!! $carts !!}
        <div class="modal-overlay" id="orderModal">
            <div class="modal-container">
                <!-- Заголовок модалки -->
                <div class="modal-header">
                    <h2>Оформление заказа</h2>
                    <button class="modal-close" onclick="closeOrderModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="form-wrapper">
                    <form class="modal-form">
                        <input type="hidden" name="formid" value="order">
                        <h3>Контактные данные</h3>

                        <div class="form-group" data-field="name">
                            <label for="name">Ваше имя <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Иван Иванов" value="{{ (isset($username) && $username !== '') ? $username : '' }}">
                        </div>

                        <div class="form-group" data-field="email">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="ivan@example.com" value="{{ (isset($email) && $email !== '') ? $email : '' }}">
                        </div>

                        <div class="form-group" data-field="phone">
                            <label for="phone">Телефон <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" class="form-control"
                                placeholder="+7 (999) 123-45-67" value="{{ (isset($phone) && $phone !== '') ? $phone : '' }}">
                        </div>

                        <h3>Способ доставки</h3>
                        <div class="delivery-options" data-field="delivery_method">
                            @forelse ($deliveries as $item)
                                @include('cart.deliveryitem', $item)
                            @empty
                                <span>Скоро тут появятся способы доставки</span>
                            @endforelse
                        </div>

                        <!-- Способ оплаты -->
                        <h3>Способ оплаты</h3>
                        <div class="payment-options" data-field="payment_method">
                            @forelse ($payments as $key => $item)
                                @include('cart.paymentitem', ['title' => $item['title'], 'code' => $key])
                            @empty
                                <span>Скоро тут появятся способы оплаты</span>
                            @endforelse
                        </div>

                        <!-- Комментарий -->
                        <div class="form-group" data-field="comment">
                            <label for="comment">Комментарий к заказу</label>
                            <textarea id="comment" name="comment" class="form-control" rows="2" placeholder="Дополнительная информация"></textarea>
                        </div>

                        <!-- Согласие -->
                        <div class="form-check" data-field="agreement">
                            <input type="checkbox" id="agreement" name="agreement" class="form-control" checked required>
                            <label for="agreement">Я согласен на обработку персональных данных</label>
                        </div>
                        <!-- Кнопка отправки -->
                        <button type="submit" name="submit" class="modal-submit-btn">
                            <span>Подтвердить заказ</span>
                        </button>
                    </form>
                </div>
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
                        value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7) + '-' + value
                            .substring(7, 9);
                    } else {
                        value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7) + '-' + value
                            .substring(7, 9) + '-' + value.substring(9, 11);
                    }

                    e.target.value = value;
                });

                // Устанавливаем курсор в конец при фокусе
                phoneInput.addEventListener('focus', function(e) {
                    const len = e.target.value.length;
                    e.target.setSelectionRange(len, len);
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
