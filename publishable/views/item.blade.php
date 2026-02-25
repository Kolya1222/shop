@extends('layout.app')

@section('styles')
    <style>
        /* Основная карточка товара */
        .product-page {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin: 60px 0px;
        }

        /* Галерея */
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

        /* Информация о товаре */
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

        .product-actions {
            display: flex;
            gap: 12px;
        }

        .product-action-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 1px solid var(--border-light);
            background: var(--white);
            color: var(--light-graphite);
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-action-btn:hover {
            background: var(--fresh-green);
            color: white;
            border-color: var(--fresh-green);
        }

        /* Рейтинг */
        .product-rating {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .stars {
            display: flex;
            gap: 4px;
            color: #fbbf24;
            font-size: 1.1rem;
        }

        .stars i {
            color: #fbbf24;
        }

        .stars i.empty {
            color: var(--border-light);
        }

        .rating-value {
            font-weight: 600;
            color: var(--deep-green);
        }

        .rating-reviews {
            color: var(--light-graphite);
            text-decoration: underline;
            cursor: pointer;
        }

        /* Цена */
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

        /* Количество */
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

        /* Кнопки покупки */
        .purchase-buttons {
            display: flex;
            gap: 16px;
            margin: 16px 0;
            flex-wrap: wrap;
        }

        .btn-buy {
            flex: 2;
            background: var(--deep-green);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            min-width: 200px;
        }

        .btn-buy:hover {
            background: var(--fresh-green);
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
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

        /* Характеристики */
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

        /* Табы */
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

        /* Отзывы */
        .reviews-summary {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .average-rating {
            text-align: center;
            min-width: 150px;
        }

        .average-number {
            font-size: 4rem;
            font-weight: 700;
            color: var(--deep-green);
            line-height: 1;
        }

        .average-stars {
            margin: 10px 0;
        }

        .rating-bars {
            flex: 1;
        }

        .rating-bar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .rating-bar-label {
            min-width: 60px;
            color: var(--light-graphite);
        }

        .rating-bar {
            flex: 1;
            height: 8px;
            background: var(--border-light);
            border-radius: 8px;
            overflow: hidden;
        }

        .rating-bar-fill {
            height: 100%;
            background: #fbbf24;
            border-radius: 8px;
        }

        .rating-bar-count {
            min-width: 40px;
            color: var(--light-graphite);
            font-size: 0.9rem;
        }

        /* Похожие товары */
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

        /* Адаптация */
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
        }

        @media (max-width: 480px) {
            .related-grid {
                grid-template-columns: 1fr;
            }

            .purchase-buttons {
                flex-direction: column;
            }

            .btn-buy,
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

            .reviews-summary {
                flex-direction: column;
                gap: 20px;
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
                <input type="hidden" name="id" value="{{$id}}" />
                <div class="product-info-header">
                    <h1>{{ $pagetitle }}</h1>
                    <div class="product-actions">
                        <button class="product-action-btn"><i class="far fa-heart"></i></button>
                        <button class="product-action-btn"><i class="far fa-share-square"></i></button>
                    </div>
                </div>

                <!-- Рейтинг -->
                <div class="product-rating">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="rating-value">4.8</span>
                    <span class="rating-reviews">156 отзывов</span>
                </div>

                <!-- Цена -->
                <div class="product-price-block">
                    <div class="current-price">
                        <span class="price">{{ evo()->runSnippet('PriceFormat', ['price' => $price]) }}</span>
                        @if ($old_price)
                            <span
                                class="old-price">{{ evo()->runSnippet('PriceFormat', ['price' => $old_price]) }}</span>
                            <span class="discount-badge">-10%</span>
                        @endif
                    </div>
                    <div class="price-info">
                        <span><i class="fas fa-check-circle"></i> В наличии</span>
                        <span><i class="fas fa-truck"></i> Бесплатная доставка</span>
                        <span><i class="fas fa-credit-card"></i> Оплата картой или наличными</span>
                    </div>
                </div>

                <!-- Количество -->
                <div class="quantity-selector">
                    <span class="quantity-label">Количество:</span>
                    <div class="quantity-controls">
                        <button class="quantity-btn"><i class="fas fa-minus"></i></button>
                        <input type="text" name="count" class="quantity-input" value="1">
                        <button class="quantity-btn"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <!-- Кнопки покупки -->
                <div class="purchase-buttons">
                    <button class="btn-buy"><i class="fas fa-bolt"></i> Купить сейчас</button>
                    <button type="submit" class="btn-cart"><i class="fas fa-shopping-cart"></i> В корзину</button>
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
                <button class="tab-btn" onclick="switchTab(2)">Отзывы (156)</button>
                <button class="tab-btn" onclick="switchTab(3)">Доставка и оплата</button>
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
                <div class="tab-pane" id="tab-2">
                    <div class="reviews-summary">
                        <div class="average-rating">
                            <div class="average-number">4.8</div>
                            <div class="average-stars">
                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                                <i class="fas fa-star-half-alt" style="color: #fbbf24;"></i>
                            </div>
                            <div>156 отзывов</div>
                        </div>
                        <div class="rating-bars">
                            <div class="rating-bar-item">
                                <span class="rating-bar-label">5</span>
                                <div class="rating-bar">
                                    <div class="rating-bar-fill" style="width: 75%"></div>
                                </div>
                                <span class="rating-bar-count">117</span>
                            </div>
                            <div class="rating-bar-item">
                                <span class="rating-bar-label">4</span>
                                <div class="rating-bar">
                                    <div class="rating-bar-fill" style="width: 20%"></div>
                                </div>
                                <span class="rating-bar-count">31</span>
                            </div>
                            <div class="rating-bar-item">
                                <span class="rating-bar-label">3</span>
                                <div class="rating-bar">
                                    <div class="rating-bar-fill" style="width: 4%"></div>
                                </div>
                                <span class="rating-bar-count">6</span>
                            </div>
                            <div class="rating-bar-item">
                                <span class="rating-bar-label">2</span>
                                <div class="rating-bar">
                                    <div class="rating-bar-fill" style="width: 1%"></div>
                                </div>
                                <span class="rating-bar-count">2</span>
                            </div>
                            <div class="rating-bar-item">
                                <span class="rating-bar-label">1</span>
                                <div class="rating-bar">
                                    <div class="rating-bar-fill" style="width: 0%"></div>
                                </div>
                                <span class="rating-bar-count">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-3">
                    <h3 style="margin-bottom: 16px; color: var(--deep-green);">Доставка и оплата</h3>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 12px;"><i class="fas fa-check"
                                style="color: var(--fresh-green); margin-right: 10px;"></i> Курьером по Москве — сегодня,
                            500 ₽</li>
                        <li style="margin-bottom: 12px;"><i class="fas fa-check"
                                style="color: var(--fresh-green); margin-right: 10px;"></i> Самовывоз (22 пункта) —
                            бесплатно</li>
                        <li style="margin-bottom: 12px;"><i class="fas fa-check"
                                style="color: var(--fresh-green); margin-right: 10px;"></i> Почтой России — 3-7 дней, 350 ₽
                        </li>
                    </ul>
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
                    <div>В ближайщее время тут появятся товары</div>
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

            const input = document.querySelector('.quantity-input');
            const minus = document.querySelector('.quantity-btn:first-child');
            const plus = document.querySelector('.quantity-btn:last-child');

            if (minus && plus && input) {
                minus.addEventListener('click', () => {
                    input.value = Math.max(1, parseInt(input.value) - 1);
                });

                plus.addEventListener('click', () => {
                    input.value = parseInt(input.value) + 1;
                });
            }

            function switchTab(tabIndex) {
                const tabButtons = document.querySelectorAll('.tab-btn');
                tabButtons.forEach(btn => btn.classList.remove('active'));

                tabButtons[tabIndex].classList.add('active');

                const tabPanes = document.querySelectorAll('.tab-pane');
                tabPanes.forEach(pane => pane.classList.remove('active'));

                const activePane = document.getElementById(`tab-${tabIndex}`);
                if (activePane) {
                    activePane.classList.add('active');
                }
            }

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
            const ratingReviews = document.querySelector('.rating-reviews');
            if (ratingReviews) {
                ratingReviews.addEventListener('click', (e) => {
                    e.preventDefault();
                    switchTab(2);
                    const tabsHeader = document.querySelector('.tabs-header');
                    if (tabsHeader) {
                        tabsHeader.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });

                ratingReviews.style.cursor = 'pointer';
            }

            function openTabFromHash() {
                const hash = window.location.hash;
                if (hash === '#reviews' || hash === '#отзывы') {
                    switchTab(2);
                } else if (hash === '#specs' || hash === '#характеристики') {
                    switchTab(1);
                } else if (hash === '#delivery' || hash === '#доставка') {
                    switchTab(3);
                } else if (hash === '#description' || hash === '#описание') {
                    switchTab(0);
                }
            }
            openTabFromHash();
            window.addEventListener('hashchange', openTabFromHash);
        });
    </script>
@endsection
