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
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Заголовок корзины -->
        <div class="cart-header">
            <h1>{{ $pagetitle }}</h1>
            <div class="cart-header-actions">
                <button class="btn-clear" data-commerce-action="clean"><i class="fas fa-trash-alt"></i> Очистить корзину</button>
            </div>
        </div>
        {!! $cart !!}
    </div>
@endsection

@section('scripts')
@endsection
