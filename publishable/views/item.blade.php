@extends('layout.app')

@section('styles')
    <style>
        .product-page {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin: 60px 0px;
        }

        .product-gallery {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .gallery-main {
            background: var(--sage);
            border-radius: 32px;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-light);
            aspect-ratio: 1/1;
        }

        .gallery-main i {
            font-size: 12rem;
            color: var(--deep-green);
            opacity: 0.9;
        }

        .gallery-thumbnails {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
        }

        .gallery-thumb {
            background: var(--sage);
            border-radius: 16px;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid transparent;
            cursor: pointer;
            transition: 0.2s;
            aspect-ratio: 1/1;
        }

        .gallery-thumb:hover {
            border-color: var(--fresh-green);
            transform: translateY(-2px);
        }

        .gallery-thumb.active {
            border-color: var(--deep-green);
            background: var(--white);
        }

        .gallery-thumb i {
            font-size: 2rem;
            color: var(--deep-green);
        }

        .product-info {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .product-info-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 16px;
        }

        .product-info-header h1 {
            font-size: 2.5rem;
            color: var(--deep-green);
            line-height: 1.2;
        }

        .product-price-block {
            background: var(--sage);
            border-radius: 32px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .current-price {
            display: flex;
            align-items: baseline;
            gap: 16px;
            flex-wrap: wrap;
        }

        .current-price .price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--deep-green);
        }

        .old-price {
            font-size: 1.5rem;
            color: var(--light-graphite);
            text-decoration: line-through;
        }

        .discount-badge {
            background: var(--fresh-green);
            color: white;
            padding: 6px 16px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .price-info {
            display: flex;
            gap: 24px;
            color: var(--light-graphite);
            font-size: 0.95rem;
            flex-wrap: wrap;
        }

        .price-info i {
            color: var(--fresh-green);
            margin-right: 6px;
        }

        .product-options {
            border: 1px solid var(--border-light);
            border-radius: 32px;
            padding: 28px;
            margin: 16px 0;
        }

        .product-options h3 {
            font-size: 1.3rem;
            color: var(--deep-green);
            margin-bottom: 20px;
        }

        .options-group {
            margin-bottom: 24px;
        }

        .options-group:last-child {
            margin-bottom: 0;
        }

        .options-label {
            display: block;
            font-weight: 600;
            color: var(--graphite);
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .option-item input[type="checkbox"]:checked+label {
            background: var(--deep-green);
            border-color: var(--deep-green);
            color: white;
        }

        .option-item input[type="checkbox"]:checked+label .option-price {
            color: white;
        }

        .option-item input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .options-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .option-item {
            position: relative;
        }

        .option-item label {
            display: block;
            padding: 10px 20px;
            background: var(--white);
            border: 2px solid var(--border-light);
            border-radius: 40px;
            font-size: 0.95rem;
            color: var(--graphite);
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            min-width: 60px;
        }

        .option-item label:hover {
            border-color: var(--fresh-green);
        }

        .option-price {
            font-size: 0.85rem;
            color: var(--fresh-green);
            margin-left: 4px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 8px 0;
        }

        .quantity-label {
            font-weight: 500;
            color: var(--graphite);
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            border: 1px solid var(--border-light);
            border-radius: 40px;
            overflow: hidden;
        }

        .quantity-btn {
            width: 44px;
            height: 44px;
            background: var(--white);
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.2s;
            color: var(--deep-green);
        }

        .quantity-btn:hover {
            background: var(--fresh-green);
            color: white;
        }

        .quantity-input {
            width: 60px;
            height: 44px;
            border: none;
            border-left: 1px solid var(--border-light);
            border-right: 1px solid var(--border-light);
            text-align: center;
            font-size: 1rem;
            font-weight: 500;
            color: var(--deep-green);
        }

        .quantity-input:focus {
            outline: none;
        }

        .purchase-buttons {
            display: flex;
            gap: 16px;
            margin: 16px 0;
            flex-wrap: wrap;
        }

        .btn-cart {
            flex: 1;
            background: transparent;
            border: 2px solid var(--deep-green);
            color: var(--deep-green);
            padding: 16px 32px;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .btn-cart:hover {
            background: var(--deep-green);
            color: white;
        }

        .product-specs-block {
            border: 1px solid var(--border-light);
            border-radius: 32px;
            padding: 28px;
            margin-top: 16px;
        }

        .product-specs-block h3 {
            font-size: 1.3rem;
            color: var(--deep-green);
            margin-bottom: 20px;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .spec-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .spec-label {
            color: var(--light-graphite);
            font-size: 0.9rem;
        }

        .spec-value {
            font-weight: 600;
            color: var(--graphite);
            font-size: 1.1rem;
        }

        .full-specs-link {
            margin-top: 24px;
            color: var(--fresh-green);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .full-specs-link:hover {
            text-decoration: underline;
        }

        .product-tabs {
            margin: 60px 0;
        }

        .tabs-header {
            display: flex;
            gap: 4px;
            border-bottom: 1px solid var(--border-light);
        }

        .tab-btn {
            padding: 16px 32px;
            background: transparent;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            color: var(--light-graphite);
            cursor: pointer;
            position: relative;
            transition: 0.2s;
        }

        .tab-btn:hover {
            color: var(--fresh-green);
        }

        .tab-btn.active {
            color: var(--deep-green);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--fresh-green);
            border-radius: 3px 3px 0 0;
        }

        .tab-content {
            padding: 40px 0;
            line-height: 1.8;
            color: var(--light-graphite);
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .related-products {
            margin: 60px 0;
        }

        .related-products h2 {
            font-size: 2rem;
            color: var(--deep-green);
            margin-bottom: 30px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        @media (max-width: 1024px) {
            .product-page {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .related-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .specs-grid {
                grid-template-columns: 1fr;
            }

            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .product-info-header h1 {
                font-size: 2rem;
            }

            .current-price .price {
                font-size: 2rem;
            }

            .gallery-main i {
                font-size: 8rem;
            }

            .options-list {
                gap: 8px;
            }

            .option-item label {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .related-grid {
                grid-template-columns: 1fr;
            }

            .purchase-buttons {
                flex-direction: column;
            }

            .btn-cart {
                width: 100%;
            }

            .tabs-header {
                flex-wrap: wrap;
            }

            .tab-btn {
                flex: 1;
                text-align: center;
                padding: 12px;
            }

            .options-group {
                margin-bottom: 16px;
            }
        }
    </style>
@endsection

@section('content')
    {!! $breadcrumbs !!}
    <div class="container">
        <!-- Основная карточка товара -->
        <div class="product-page">
            <!-- Левая колонка: галерея -->
            <div class="product-gallery">
                @php
                    $images = json_decode($product_gallery, true)['fieldValue'] ?? [];
                @endphp
                @if (empty($images[0]['image']))
                    <div class="gallery-main">
                        <i class="fas fa-laptop"></i>
                    </div>
                @else
                    <div class="gallery-main">
                        <i class="{{ $images[0]['image'] ?? '' }}"></i>
                    </div>
                @endif
                <div class="gallery-thumbnails">
                    @forelse ($images as $item)
                        <div class="gallery-thumb {{ $loop->first ? 'active' : '' }}" data-icon="{{ $item['image'] }}">
                            <i class="{{ $item['image'] }}"></i>
                        </div>
                    @empty
                        <div class="gallery-thumb active" data-icon="fas fa-laptop">
                            <i class="fas fa-laptop"></i>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Правая колонка: информация -->
            <form class="product-info" action="#" data-commerce-action="add">
                <input type="hidden" name="id" value="{{ $id }}" />
                <div class="product-info-header">
                    <h1>{{ $pagetitle }}</h1>
                </div>

                <!-- Цена -->
                <div class="product-price-block">
                    <div class="current-price">
                        <span class="price">@price($price)</span>
                        @if ($old_price)
                            <span class="old-price">@price($old_price)</span>
                            <span class="discount-badge">-10%</span>
                        @endif
                    </div>
                    <!--<div class="price-info">
                                    <span><i class="fas fa-check-circle"></i> В наличии</span>
                                    <span><i class="fas fa-truck"></i> Бесплатная доставка</span>
                                    <span><i class="fas fa-credit-card"></i> Оплата картой или наличными</span>
                                </div>-->
                </div>

                <!-- Опции товара -->
                @if (!empty($options) and $options != '[]')
                    <div class="product-options">
                        <h3>Выберите опции</h3>
                        @php
                            $options = json_decode($options, true)['fieldValue'] ?? [];
                        @endphp
                        <!-- Группа опций -->
                        <div class="options-group">
                            <div class="options-list">
                                @forelse ($options as $item)
                                    <div class="option-item">
                                        <input type="checkbox" name="options[options_{{ $loop->iteration }}]"
                                            id="options_{{ $loop->iteration }}" value="{{ $item['name'] }}">
                                        <label for="options_{{ $loop->iteration }}">
                                            {{ $item['name'] }}
                                            <span class="option-price">(+{{ $item['value'] }} ₽)</span>
                                        </label>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Количество -->
                <div class="quantity-selector">
                    <span class="quantity-label">Количество:</span>
                    <div class="quantity-controls">
                        <button type="button" class="quantity-btn" onclick="decrementQuantity()"><i
                                class="fas fa-minus"></i></button>
                        <input type="number" name="count" class="quantity-input" value="1" min="1"
                            max="99" step="1">
                        <button type="button" class="quantity-btn" onclick="incrementQuantity()"><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>

                <!-- Кнопки покупки -->
                <div class="purchase-buttons">
                    <button type="submit" name="action" value="cart" class="btn-cart">
                        <i class="fas fa-shopping-cart"></i>
                        В корзину
                    </button>
                </div>

                <!-- Краткие характеристики -->
                @php
                    $parameters = json_decode($parameters, true)['fieldValue'] ?? [];
                @endphp
                <div class="product-specs-block">
                    <h3>Характеристики</h3>
                    <div class="specs-grid">
                        @forelse ($parameters as $item)
                            <div class="spec-item">
                                <span class="spec-label">{{ $item['name'] }}</span>
                                <span class="spec-value">{{ $item['value'] }}</span>
                            </div>
                            @break($loop->index == 3)
                        @empty
                            <div>В данный момент характеристики не доступны</div>
                        @endforelse
                    </div>
                    <a href="#" class="full-specs-link">Все характеристики <i class="fas fa-arrow-right"></i></a>
                </div>
            </form>
        </div>

        <!-- Табы с детальной информацией -->
        <div class="product-tabs">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab(0)">Описание</button>
                <button class="tab-btn" onclick="switchTab(1)">Характеристики</button>
            </div>

            <div class="tab-content">
                <div class="tab-pane active" id="tab-0">
                    @if (empty($content))
                        <div>Описание появится в ближайшее время</div>
                    @else
                        {!! $content !!}
                    @endif
                </div>
                <div class="tab-pane" id="tab-1">
                    <h3 style="margin-bottom: 16px; color: var(--deep-green);">Полные характеристики</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        @forelse ($parameters as $item)
                            <div><strong>{{ $item['name'] }}:</strong> {{ $item['value'] }}</div>
                        @empty
                            <div>В данный момент характеристики не доступны</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Похожие товары -->
        <div class="related-products">
            <h2>Новинки этой недели</h2>
            <div class="related-grid">
                @forelse ($products as $item)
                    @include('parts.itemcard', $item)
                @empty
                    <div>В ближайшее время тут появятся товары</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.querySelector('.gallery-main i');
            const thumbnails = document.querySelectorAll('.gallery-thumb');

            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    const iconClass = this.dataset.icon;
                    mainImage.className = iconClass;
                });
            });

            function calculateDiscount() {
                const priceEl = document.querySelector('.current-price .price');
                const oldPriceEl = document.querySelector('.old-price');
                const discountEl = document.querySelector('.discount-badge');

                if (oldPriceEl && discountEl) {
                    const currentPrice = parseFloat(priceEl.textContent.replace(/[^\d,.]/g, '').replace(',', '.'));
                    const oldPrice = parseFloat(oldPriceEl.textContent.replace(/[^\d,.]/g, '').replace(',', '.'));

                    if (oldPrice > currentPrice) {
                        const discount = Math.round(((oldPrice - currentPrice) / oldPrice) * 100);
                        discountEl.textContent = `-${discount}%`;
                    }
                }
            }

            calculateDiscount();

            window.decrementQuantity = function() {
                const input = document.querySelector('.quantity-input');
                const currentValue = parseInt(input.value) || 1;
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                }
            };

            window.incrementQuantity = function() {
                const input = document.querySelector('.quantity-input');
                const currentValue = parseInt(input.value) || 1;
                if (currentValue < 99) {
                    input.value = currentValue + 1;
                }
            };

            const quantityInput = document.querySelector('.quantity-input');
            if (quantityInput) {
                quantityInput.addEventListener('change', function() {
                    let value = parseInt(this.value) || 1;
                    value = Math.max(1, Math.min(99, value));
                    this.value = value;
                });
            }

            window.switchTab = function(tabIndex) {
                const tabButtons = document.querySelectorAll('.tab-btn');
                tabButtons.forEach(btn => btn.classList.remove('active'));

                if (tabButtons[tabIndex]) {
                    tabButtons[tabIndex].classList.add('active');
                }

                const tabPanes = document.querySelectorAll('.tab-pane');
                tabPanes.forEach(pane => pane.classList.remove('active'));

                const activePane = document.getElementById(`tab-${tabIndex}`);
                if (activePane) {
                    activePane.classList.add('active');
                }
            };

            const tabButtons = document.querySelectorAll('.tab-btn');
            tabButtons.forEach((button, index) => {
                button.removeAttribute('onclick');
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    switchTab(index);
                });
            });

            const fullSpecsLink = document.querySelector('.full-specs-link');
            if (fullSpecsLink) {
                fullSpecsLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    switchTab(1);
                    const tabsHeader = document.querySelector('.tabs-header');
                    if (tabsHeader) {
                        tabsHeader.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            }
        });
    </script>
@endsection
