@extends('layout.app')

@section('styles')
    <style>
        /* Заголовок страницы */
        .wishlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0 40px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .wishlist-header h1 {
            font-size: 2.5rem;
            color: var(--deep-green);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .wishlist-header h1 i {
            color: #ef4444;
            font-size: 2.2rem;
        }

        .wishlist-count {
            background: var(--sage);
            padding: 8px 20px;
            border-radius: 40px;
            color: var(--deep-green);
            font-weight: 600;
            font-size: 1rem;
        }

        .wishlist-actions {
            display: flex;
            gap: 16px;
        }

        .btn-share {
            background: transparent;
            border: 1px solid var(--deep-green);
            color: var(--deep-green);
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-share:hover {
            background: var(--deep-green);
            color: white;
        }

        .btn-clear-wishlist {
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

        .btn-clear-wishlist:hover {
            background: #ef4444;
            color: white;
        }

        /* Фильтры избранного */
        .wishlist-filters {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .wishlist-filter-btn {
            padding: 10px 24px;
            border-radius: 40px;
            border: 1px solid var(--border-light);
            background: var(--white);
            color: var(--light-graphite);
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .wishlist-filter-btn i {
            color: var(--fresh-green);
        }

        .wishlist-filter-btn:hover {
            border-color: var(--fresh-green);
            color: var(--deep-green);
        }

        .wishlist-filter-btn.active {
            background: var(--deep-green);
            color: white;
            border-color: var(--deep-green);
        }

        .wishlist-filter-btn.active i {
            color: white;
        }

        /* Раскладка избранного */
        .wishlist-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            margin-bottom: 60px;
        }

        /* Боковая панель с категориями */
        .wishlist-sidebar {
            background: var(--white);
            border-radius: 32px;
            border: 1px solid var(--border-light);
            padding: 24px;
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .wishlist-sidebar h3 {
            font-size: 1.2rem;
            color: var(--deep-green);
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-light);
        }

        .wishlist-categories {
            list-style: none;
        }

        .wishlist-category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            color: var(--light-graphite);
            cursor: pointer;
            transition: 0.2s;
            border-bottom: 1px dashed var(--border-light);
        }

        .wishlist-category-item:hover {
            color: var(--fresh-green);
            padding-left: 8px;
        }

        .wishlist-category-item.active {
            color: var(--deep-green);
            font-weight: 600;
        }

        .wishlist-category-item .count {
            background: var(--sage);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .wishlist-category-item.active .count {
            background: var(--deep-green);
            color: white;
        }

        .wishlist-availability {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
        }

        .availability-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            color: var(--light-graphite);
            cursor: pointer;
        }

        .availability-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            accent-color: var(--fresh-green);
        }

        /* Сетка избранного */
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }

        /* Карточка для избранного (расширенная версия) */
        .wishlist-card {
            background: var(--white);
            border-radius: 32px;
            border: 1px solid var(--border-light);
            padding: 20px;
            transition: 0.3s;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .wishlist-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow);
            border-color: var(--fresh-green);
        }

        .wishlist-card-badge {
            position: absolute;
            top: 16px;
            left: 20px;
            background: var(--fresh-green);
            color: white;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 2;
        }

        .wishlist-card-remove {
            position: absolute;
            top: 16px;
            right: 20px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--white);
            border: 1px solid var(--border-light);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
            z-index: 3;
            font-size: 1rem;
        }

        .wishlist-card-remove:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
        }

        .wishlist-card-image {
            width: 100%;
            aspect-ratio: 1;
            background: var(--sage);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: var(--deep-green);
            font-size: 4rem;
        }

        .wishlist-card-category {
            color: var(--fresh-green);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .wishlist-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--deep-green);
            text-decoration: none;
        }

        .wishlist-card-title:hover {
            color: var(--fresh-green);
        }

        .wishlist-card-spec {
            color: var(--light-graphite);
            font-size: 0.85rem;
            margin-bottom: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .wishlist-card-spec span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .wishlist-card-spec i {
            color: var(--fresh-green);
            font-size: 0.7rem;
        }

        .wishlist-card-stock {
            font-size: 0.85rem;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .in-stock {
            color: var(--fresh-green);
        }

        .out-of-stock {
            color: #ef4444;
        }

        .wishlist-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            border-top: 1px dashed var(--border-light);
            padding-top: 16px;
        }

        .wishlist-card-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--deep-green);
        }

        .wishlist-card-old-price {
            font-size: 0.9rem;
            color: var(--light-graphite);
            text-decoration: line-through;
            margin-left: 8px;
        }

        .wishlist-card-add {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--sage);
            border: none;
            color: var(--deep-green);
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .wishlist-card-add:hover {
            background: var(--fresh-green);
            color: white;
        }

        /* Пагинация для избранного */
        .wishlist-pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 40px;
        }

        /* Пустое избранное */
        .empty-wishlist {
            text-align: center;
            padding: 80px 20px;
            background: var(--white);
            border-radius: 48px;
            border: 1px solid var(--border-light);
            margin: 40px 0;
        }

        .empty-wishlist i {
            font-size: 6rem;
            color: #ef4444;
            opacity: 0.5;
            margin-bottom: 24px;
        }

        .empty-wishlist h2 {
            font-size: 2rem;
            color: var(--deep-green);
            margin-bottom: 16px;
        }

        .empty-wishlist p {
            color: var(--light-graphite);
            margin-bottom: 32px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .empty-wishlist .btn-primary {
            display: inline-flex;
        }

        /* Уведомление о добавлении в корзину */
        .wishlist-toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--deep-green);
            color: white;
            padding: 16px 24px;
            border-radius: 60px;
            box-shadow: var(--hover-shadow);
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateY(100px);
            opacity: 0;
            transition: 0.3s;
            z-index: 1000;
        }

        .wishlist-toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .wishlist-toast i {
            color: var(--fresh-green);
            font-size: 1.2rem;
        }

        /* Адаптация */
        @media (max-width: 1200px) {
            .wishlist-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 900px) {
            .wishlist-layout {
                grid-template-columns: 1fr;
            }

            .wishlist-sidebar {
                position: static;
                margin-bottom: 20px;
            }

            .wishlist-header h1 {
                font-size: 2rem;
            }
        }

        @media (max-width: 600px) {
            .wishlist-grid {
                grid-template-columns: 1fr;
            }

            .wishlist-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .wishlist-actions {
                width: 100%;
                flex-direction: column;
            }

            .btn-share,
            .btn-clear-wishlist {
                width: 100%;
                justify-content: center;
            }

            .wishlist-filters {
                overflow-x: auto;
                padding-bottom: 8px;
                flex-wrap: nowrap;
            }

            .wishlist-filter-btn {
                white-space: nowrap;
            }

            .wishlist-toast {
                left: 20px;
                right: 20px;
                bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            .wishlist-card-footer {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .wishlist-card-add {
                width: 100%;
                border-radius: 40px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Заголовок страницы -->
        <div class="wishlist-header">
            <h1>
                <i class="fas fa-heart"></i> Избранное
                <span class="wishlist-count">12 товаров</span>
            </h1>
            <div class="wishlist-actions">
                <button class="btn-share"><i class="fas fa-share-alt"></i> Поделиться списком</button>
                <button class="btn-clear-wishlist"><i class="fas fa-trash-alt"></i> Очистить список</button>
            </div>
        </div>

        <!-- Быстрые фильтры -->
        <div class="wishlist-filters">
            <button class="wishlist-filter-btn active"><i class="fas fa-heart"></i> Все товары</button>
            <button class="wishlist-filter-btn"><i class="fas fa-tag"></i> Со скидкой</button>
            <button class="wishlist-filter-btn"><i class="fas fa-check-circle"></i> В наличии</button>
            <button class="wishlist-filter-btn"><i class="fas fa-truck"></i> С быстрой доставкой</button>
            <button class="wishlist-filter-btn"><i class="fas fa-star"></i> Новинки</button>
        </div>

        <!-- Основная раскладка -->
        <div class="wishlist-layout">
            <!-- Боковая панель с категориями -->
            <aside class="wishlist-sidebar">
                <h3>Категории</h3>
                <ul class="wishlist-categories">
                    <li class="wishlist-category-item active">
                        Все категории <span class="count">12</span>
                    </li>
                    <li class="wishlist-category-item">
                        Ноутбуки <span class="count">4</span>
                    </li>
                    <li class="wishlist-category-item">
                        Комплектующие <span class="count">3</span>
                    </li>
                    <li class="wishlist-category-item">
                        Периферия <span class="count">3</span>
                    </li>
                    <li class="wishlist-category-item">
                        Мониторы <span class="count">1</span>
                    </li>
                    <li class="wishlist-category-item">
                        Сетевое оборудование <span class="count">1</span>
                    </li>
                </ul>

                <div class="wishlist-availability">
                    <h3 style="margin-bottom: 16px;">Наличие</h3>
                    <label class="availability-item">
                        <input type="checkbox" checked> В наличии
                    </label>
                    <label class="availability-item">
                        <input type="checkbox"> Под заказ
                    </label>
                    <label class="availability-item">
                        <input type="checkbox"> Ожидается поступление
                    </label>
                </div>
            </aside>

            <!-- Сетка избранных товаров -->
            <div class="wishlist-content">
                <div class="wishlist-grid">
                    <!-- Карточка 1 -->
                    <div class="wishlist-card">
                        <span class="wishlist-card-badge">Хит</span>
                        <button class="wishlist-card-remove"><i class="fas fa-times"></i></button>
                        <div class="wishlist-card-image">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div class="wishlist-card-category">Ноутбуки / Apple</div>
                        <a href="#" class="wishlist-card-title">MacBook Pro 16" M3 Max</a>
                        <div class="wishlist-card-spec">
                            <span><i class="fas fa-microchip"></i> M3 Max</span>
                            <span><i class="fas fa-memory"></i> 48GB</span>
                            <span><i class="fas fa-database"></i> 1TB</span>
                        </div>
                        <div class="wishlist-card-stock in-stock">
                            <i class="fas fa-check-circle"></i> В наличии
                        </div>
                        <div class="wishlist-card-footer">
                            <div>
                                <span class="wishlist-card-price">₽349 990</span>
                                <span class="wishlist-card-old-price">₽389 990</span>
                            </div>
                            <button class="wishlist-card-add" title="Добавить в корзину"><i
                                    class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>

                    <!-- Карточка 2 -->
                    <div class="wishlist-card">
                        <button class="wishlist-card-remove"><i class="fas fa-times"></i></button>
                        <div class="wishlist-card-image">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <div class="wishlist-card-category">Процессоры / AMD</div>
                        <a href="#" class="wishlist-card-title">AMD Ryzen 7 7800X3D</a>
                        <div class="wishlist-card-spec">
                            <span><i class="fas fa-microchip"></i> 8 ядер</span>
                            <span><i class="fas fa-microchip"></i> 16 потоков</span>
                            <span><i class="fas fa-clock"></i> 5.0 GHz</span>
                        </div>
                        <div class="wishlist-card-stock in-stock">
                            <i class="fas fa-check-circle"></i> В наличии
                        </div>
                        <div class="wishlist-card-footer">
                            <span class="wishlist-card-price">₽42 990</span>
                            <button class="wishlist-card-add"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>

                    <!-- Карточка 3 -->
                    <div class="wishlist-card">
                        <span class="wishlist-card-badge">-15%</span>
                        <button class="wishlist-card-remove"><i class="fas fa-times"></i></button>
                        <div class="wishlist-card-image">
                            <i class="fas fa-memory"></i>
                        </div>
                        <div class="wishlist-card-category">SSD / Samsung</div>
                        <a href="#" class="wishlist-card-title">Samsung 990 Pro 2TB</a>
                        <div class="wishlist-card-spec">
                            <span><i class="fas fa-bolt"></i> 7450 МБ/с</span>
                            <span><i class="fas fa-microchip"></i> NVMe</span>
                        </div>
                        <div class="wishlist-card-stock in-stock">
                            <i class="fas fa-check-circle"></i> В наличии
                        </div>
                        <div class="wishlist-card-footer">
                            <div>
                                <span class="wishlist-card-price">₽18 490</span>
                                <span class="wishlist-card-old-price">₽21 990</span>
                            </div>
                            <button class="wishlist-card-add"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>

                    <!-- Карточка 4 -->
                    <div class="wishlist-card">
                        <button class="wishlist-card-remove"><i class="fas fa-times"></i></button>
                        <div class="wishlist-card-image">
                            <i class="fas fa-keyboard"></i>
                        </div>
                        <div class="wishlist-card-category">Клавиатуры / Keychron</div>
                        <a href="#" class="wishlist-card-title">Keychron K3 Pro</a>
                        <div class="wishlist-card-spec">
                            <span><i class="fas fa-bluetooth"></i> Беспроводная</span>
                            <span><i class="fas fa-lightbulb"></i> RGB</span>
                        </div>
                        <div class="wishlist-card-stock out-of-stock">
                            <i class="fas fa-times-circle"></i> Нет в наличии
                        </div>
                        <div class="wishlist-card-footer">
                            <span class="wishlist-card-price">₽8 990</span>
                            <button class="wishlist-card-add" disabled style="opacity: 0.5; cursor: not-allowed;"><i
                                    class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>

                    <!-- Карточка 5 -->
                    <div class="wishlist-card">
                        <button class="wishlist-card-remove"><i class="fas fa-times"></i></button>
                        <div class="wishlist-card-image">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div class="wishlist-card-category">Мониторы / LG</div>
                        <a href="#" class="wishlist-card-title">LG UltraGear 27"</a>
                        <div class="wishlist-card-spec">
                            <span><i class="fas fa-chart-line"></i> 240Hz</span>
                            <span><i class="fas fa-tv"></i> QHD</span>
                            <span><i class="fas fa-clock"></i> 1ms</span>
                        </div>
                        <div class="wishlist-card-stock in-stock">
                            <i class="fas fa-check-circle"></i> В наличии
                        </div>
                        <div class="wishlist-card-footer">
                            <span class="wishlist-card-price">₽37 990</span>
                            <button class="wishlist-card-add"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>

                    <!-- Карточка 6 -->
                    <div class="wishlist-card">
                        <span class="wishlist-card-badge">NEW</span>
                        <button class="wishlist-card-remove"><i class="fas fa-times"></i></button>
                        <div class="wishlist-card-image">
                            <i class="fas fa-mouse"></i>
                        </div>
                        <div class="wishlist-card-category">Мыши / Logitech</div>
                        <a href="#" class="wishlist-card-title">Logitech G Pro X Superlight 2</a>
                        <div class="wishlist-card-spec">
                            <span><i class="fas fa-bluetooth"></i> Беспроводная</span>
                            <span><i class="fas fa-bolt"></i> 32K DPI</span>
                        </div>
                        <div class="wishlist-card-stock in-stock">
                            <i class="fas fa-check-circle"></i> В наличии
                        </div>
                        <div class="wishlist-card-footer">
                            <span class="wishlist-card-price">₽14 990</span>
                            <button class="wishlist-card-add"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Пагинация -->
                <div class="wishlist-pagination">
                    <a href="#" class="pagination-item active">1</a>
                    <a href="#" class="pagination-item">2</a>
                    <a href="#" class="pagination-item">3</a>
                    <a href="#" class="pagination-item">4</a>
                    <a href="#" class="pagination-item next">Далее <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Уведомление -->
        <div class="wishlist-toast" id="wishlistToast">
            <i class="fas fa-check-circle"></i>
            <span>Товар добавлен в корзину</span>
        </div>

        <!-- Пример пустого избранного (закомментирован)
            <div class="empty-wishlist">
                <i class="fas fa-heart-broken"></i>
                <h2>Список избранного пуст</h2>
                <p>Добавляйте товары в избранное, чтобы не потерять их и быстро находить понравившиеся позиции</p>
                <a href="#" class="btn-primary"><i class="fas fa-arrow-right"></i> Перейти в каталог</a>
            </div>
            -->
    </div>
@endsection

@section('scripts')
@endsection
