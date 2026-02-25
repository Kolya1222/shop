@extends('layout.app')

@section('styles')
<style>
    /* Заголовок страницы */
    .promo-header {
        margin: 30px 0 40px;
    }

    .promo-header h1 {
        font-size: 2.5rem;
        color: var(--deep-green);
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }

    .promo-header h1 i {
        color: #ef4444;
        font-size: 2.2rem;
    }

    .promo-header p {
        color: var(--light-graphite);
        font-size: 1.1rem;
        max-width: 600px;
    }

    /* Баннер главной акции */
    .promo-main-banner {
        background: linear-gradient(135deg, #0b2b26 0%, #1a4a3a 100%);
        border-radius: 48px;
        padding: 48px;
        margin-bottom: 50px;
        color: white;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .promo-main-banner::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        pointer-events: none;
    }

    .promo-main-content h2 {
        font-size: 2.8rem;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .promo-main-content h2 span {
        color: var(--fresh-green);
        display: block;
    }

    .promo-main-content p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .promo-main-timer {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 80px;
        padding: 24px 32px;
        display: inline-flex;
        gap: 32px;
        margin-bottom: 30px;
    }

    .timer-item {
        text-align: center;
    }

    .timer-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--fresh-green);
        line-height: 1;
    }

    .timer-label {
        font-size: 0.9rem;
        opacity: 0.8;
        text-transform: uppercase;
    }

    .promo-main-image {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .promo-main-image i {
        font-size: 15rem;
        color: var(--fresh-green);
        filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.3));
    }

    /* Фильтры акций */
    .promo-filters {
        display: flex;
        gap: 12px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .promo-filter-btn {
        padding: 12px 28px;
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

    .promo-filter-btn i {
        color: #ef4444;
    }

    .promo-filter-btn:hover {
        border-color: #ef4444;
        color: var(--deep-green);
    }

    .promo-filter-btn.active {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    .promo-filter-btn.active i {
        color: white;
    }

    /* Сетка акций */
    .promo-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 50px;
    }

    /* Карточка акции */
    .promo-card {
        background: var(--white);
        border-radius: 32px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        transition: 0.3s;
        position: relative;
    }

    .promo-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow);
        border-color: #ef4444;
    }

    .promo-card-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: #ef4444;
        color: white;
        padding: 8px 20px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 1rem;
        z-index: 2;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .promo-card-image {
        height: 200px;
        background: linear-gradient(135deg, var(--sage) 0%, #d1fae5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .promo-card-image i {
        font-size: 6rem;
        color: var(--deep-green);
        opacity: 0.9;
    }

    .promo-discount {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #ef4444;
        color: white;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .promo-discount span {
        font-size: 1.5rem;
        line-height: 1;
    }

    .promo-discount small {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .promo-card-content {
        padding: 24px;
    }

    .promo-card-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--deep-green);
        margin-bottom: 12px;
    }

    .promo-card-desc {
        color: var(--light-graphite);
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .promo-card-meta {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .promo-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--light-graphite);
        font-size: 0.95rem;
    }

    .promo-meta-item i {
        color: #ef4444;
    }

    .promo-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px dashed var(--border-light);
        padding-top: 20px;
    }

    .promo-code {
        background: var(--sage);
        padding: 8px 16px;
        border-radius: 30px;
        font-family: monospace;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--deep-green);
        letter-spacing: 1px;
    }

    .promo-btn {
        background: transparent;
        border: 1px solid var(--deep-green);
        color: var(--deep-green);
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .promo-btn:hover {
        background: var(--deep-green);
        color: white;
    }

    /* Товары по акции */
    .promo-products-section {
        margin: 60px 0;
    }

    .promo-products-section h2 {
        font-size: 2rem;
        color: var(--deep-green);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .promo-products-section h2 i {
        color: #ef4444;
    }

    .promo-products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    /* Прогресс-бар акции */
    .promo-progress {
        margin: 20px 0;
    }

    .promo-progress-header {
        display: flex;
        justify-content: space-between;
        color: var(--light-graphite);
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .promo-progress-bar {
        height: 8px;
        background: var(--border-light);
        border-radius: 8px;
        overflow: hidden;
    }

    .promo-progress-fill {
        height: 100%;
        background: #ef4444;
        border-radius: 8px;
        width: 75%;
    }

    /* Таймер в карточке */
    .promo-card-timer {
        display: flex;
        gap: 8px;
        margin: 15px 0;
    }

    .promo-timer-block {
        flex: 1;
        background: var(--sage);
        border-radius: 12px;
        padding: 8px 4px;
        text-align: center;
    }

    .promo-timer-number {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--deep-green);
        line-height: 1;
    }

    .promo-timer-label {
        font-size: 0.7rem;
        color: var(--light-graphite);
        text-transform: uppercase;
    }

    /* Пагинация */
    .promo-pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin: 50px 0 30px;
    }

    /* Адаптация */
    @media (max-width: 1200px) {
        .promo-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .promo-products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 900px) {
        .promo-main-banner {
            grid-template-columns: 1fr;
            text-align: center;
            padding: 40px 24px;
        }
        
        .promo-main-timer {
            margin-left: auto;
            margin-right: auto;
        }
        
        .promo-main-content h2 span {
            display: inline;
        }
        
        .promo-products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .promo-header h1 {
            font-size: 2rem;
        }
        
        .promo-grid {
            grid-template-columns: 1fr;
        }
        
        .promo-products-grid {
            grid-template-columns: 1fr;
        }
        
        .promo-main-timer {
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }
        
        .promo-main-content h2 {
            font-size: 2rem;
        }
        
        .promo-main-image i {
            font-size: 10rem;
        }
        
        .promo-filters {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 8px;
        }
        
        .promo-filter-btn {
            white-space: nowrap;
        }
        
        .promo-card-footer {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }
        
        .promo-code {
            width: 100%;
            text-align: center;
        }
        
        .promo-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>   
@endsection

@section('content')
    <div class="container">
        <!-- Заголовок страницы -->
        <div class="promo-header">
            <h1>
                <i class="fas fa-fire"></i> {{$pagetitle}}
            </h1>
            <p>{{$introtext}}</p>
        </div>

        <!-- Главный баннер акции -->
        <div class="promo-main-banner">
            <div class="promo-main-content">
                <h2>
                    <span>Черная пятница</span>
                    в IT-маркете
                </h2>
                <p>Скидки до 70% на тысячи товаров. Только 7 дней!</p>
                
                <div class="promo-main-timer">
                    <div class="timer-item">
                        <div class="timer-value">03</div>
                        <div class="timer-label">Дней</div>
                    </div>
                    <div class="timer-item">
                        <div class="timer-value">12</div>
                        <div class="timer-label">Часов</div>
                    </div>
                    <div class="timer-item">
                        <div class="timer-value">45</div>
                        <div class="timer-label">Минут</div>
                    </div>
                    <div class="timer-item">
                        <div class="timer-value">22</div>
                        <div class="timer-label">Секунд</div>
                    </div>
                </div>

                <a href="#" class="btn-primary" style="background: #ef4444;">
                    <i class="fas fa-bolt"></i> Смотреть все предложения
                </a>
            </div>
            <div class="promo-main-image">
                <i class="fas fa-gift"></i>
            </div>
        </div>

        <!-- Фильтры акций -->
        <div class="promo-filters">
            <button class="promo-filter-btn active"><i class="fas fa-fire"></i> Все акции</button>
            <button class="promo-filter-btn"><i class="fas fa-tag"></i> Скидки до 50%</button>
            <button class="promo-filter-btn"><i class="fas fa-gift"></i> Подарки</button>
            <button class="promo-filter-btn"><i class="fas fa-bolt"></i> Молниеносные</button>
            <button class="promo-filter-btn"><i class="fas fa-truck"></i> Бесплатная доставка</button>
            <button class="promo-filter-btn"><i class="fas fa-credit-card"></i> Спеццена по карте</button>
        </div>

        <!-- Сетка акций -->
        <div class="promo-grid">
            <!-- Карточка акции 1 -->
            <div class="promo-card">
                <div class="promo-card-badge">Горячее предложение</div>
                <div class="promo-card-image">
                    <i class="fas fa-laptop"></i>
                    <div class="promo-discount">
                        <span>-30%</span>
                    </div>
                </div>
                <div class="promo-card-content">
                    <h3 class="promo-card-title">Скидка на ноутбуки Apple</h3>
                    <p class="promo-card-desc">Все модели MacBook со скидкой до 30%. Успей купить технику Apple по выгодной цене.</p>
                    
                    <div class="promo-card-meta">
                        <span class="promo-meta-item"><i class="fas fa-calendar-alt"></i> До 31 марта</span>
                        <span class="promo-meta-item"><i class="fas fa-box"></i> 24 товара</span>
                    </div>

                    <div class="promo-progress">
                        <div class="promo-progress-header">
                            <span>Осталось товаров</span>
                            <span>18 из 24</span>
                        </div>
                        <div class="promo-progress-bar">
                            <div class="promo-progress-fill" style="width: 75%"></div>
                        </div>
                    </div>

                    <div class="promo-card-footer">
                        <span class="promo-code">APPLE30</span>
                        <a href="#" class="promo-btn">Подробнее <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Карточка акции 2 -->
            <div class="promo-card">
                <div class="promo-card-image">
                    <i class="fas fa-microchip"></i>
                    <div class="promo-discount">
                        <span>1+1</span>
                        <small>=3</small>
                    </div>
                </div>
                <div class="promo-card-content">
                    <h3 class="promo-card-title">Комплектующие: 3 по цене 2</h3>
                    <p class="promo-card-desc">При покупке двух процессоров или видеокарт — третья в подарок. Выбирай комплектующие для сборки.</p>
                    
                    <div class="promo-card-meta">
                        <span class="promo-meta-item"><i class="fas fa-calendar-alt"></i> До 15 апреля</span>
                        <span class="promo-meta-item"><i class="fas fa-box"></i> 36 товаров</span>
                    </div>

                    <div class="promo-card-timer">
                        <div class="promo-timer-block">
                            <div class="promo-timer-number">03</div>
                            <div class="promo-timer-label">дня</div>
                        </div>
                        <div class="promo-timer-block">
                            <div class="promo-timer-number">18</div>
                            <div class="promo-timer-label">часов</div>
                        </div>
                        <div class="promo-timer-block">
                            <div class="promo-timer-number">45</div>
                            <div class="promo-timer-label">мин</div>
                        </div>
                    </div>

                    <div class="promo-card-footer">
                        <span class="promo-code">3FOR2</span>
                        <a href="#" class="promo-btn">Подробнее <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Карточка акции 3 -->
            <div class="promo-card">
                <div class="promo-card-badge">Хит</div>
                <div class="promo-card-image">
                    <i class="fas fa-keyboard"></i>
                    <div class="promo-discount">
                        <span>-50%</span>
                    </div>
                </div>
                <div class="promo-card-content">
                    <h3 class="promo-card-title">Периферия со скидкой 50%</h3>
                    <p class="promo-card-desc">Мыши, клавиатуры, наушники и веб-камеры. Топовые бренды по половинной цене.</p>
                    
                    <div class="promo-card-meta">
                        <span class="promo-meta-item"><i class="fas fa-calendar-alt"></i> До 20 марта</span>
                        <span class="promo-meta-item"><i class="fas fa-box"></i> 52 товара</span>
                    </div>

                    <div class="promo-progress">
                        <div class="promo-progress-header">
                            <span>Осталось товаров</span>
                            <span>23 из 52</span>
                        </div>
                        <div class="promo-progress-bar">
                            <div class="promo-progress-fill" style="width: 44%"></div>
                        </div>
                    </div>

                    <div class="promo-card-footer">
                        <span class="promo-code">PERIPH50</span>
                        <a href="#" class="promo-btn">Подробнее <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Карточка акции 4 -->
            <div class="promo-card">
                <div class="promo-card-image">
                    <i class="fas fa-truck"></i>
                    <div class="promo-discount" style="background: var(--fresh-green);">
                        <span>0₽</span>
                    </div>
                </div>
                <div class="promo-card-content">
                    <h3 class="promo-card-title">Бесплатная доставка</h3>
                    <p class="promo-card-desc">При заказе от 10 000 ₽ доставка по всей России бесплатно. Торопись, предложение ограничено!</p>
                    
                    <div class="promo-card-meta">
                        <span class="promo-meta-item"><i class="fas fa-calendar-alt"></i> Постоянно</span>
                        <span class="promo-meta-item"><i class="fas fa-ruble-sign"></i> От 10 000 ₽</span>
                    </div>

                    <div class="promo-card-footer">
                        <span class="promo-code">FREESHIP</span>
                        <a href="#" class="promo-btn">Подробнее <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Карточка акции 5 -->
            <div class="promo-card">
                <div class="promo-card-badge">Молниеносная</div>
                <div class="promo-card-image">
                    <i class="fas fa-clock"></i>
                    <div class="promo-discount">
                        <span>-70%</span>
                    </div>
                </div>
                <div class="promo-card-content">
                    <h3 class="promo-card-title">Flash-скидки каждый час</h3>
                    <p class="promo-card-desc">Каждый час новые товары со скидкой до 70%. Следи за обновлениями!</p>
                    
                    <div class="promo-card-timer">
                        <div class="promo-timer-block">
                            <div class="promo-timer-number">00</div>
                            <div class="promo-timer-label">часов</div>
                        </div>
                        <div class="promo-timer-block">
                            <div class="promo-timer-number">47</div>
                            <div class="promo-timer-label">минут</div>
                        </div>
                        <div class="promo-timer-block">
                            <div class="promo-timer-number">32</div>
                            <div class="promo-timer-label">сек</div>
                        </div>
                    </div>

                    <div class="promo-card-footer">
                        <span class="promo-code">FLASH</span>
                        <a href="#" class="promo-btn">Подробнее <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Карточка акции 6 -->
            <div class="promo-card">
                <div class="promo-card-image">
                    <i class="fas fa-credit-card"></i>
                    <div class="promo-discount" style="background: var(--deep-green);">
                        <span>10%</span>
                    </div>
                </div>
                <div class="promo-card-content">
                    <h3 class="promo-card-title">Кешбэк 10% за оплату картой</h3>
                    <p class="promo-card-desc">При оплате картой нашего банка — кешбэк 10% баллами на следующие покупки.</p>
                    
                    <div class="promo-card-meta">
                        <span class="promo-meta-item"><i class="fas fa-calendar-alt"></i> До 31 мая</span>
                        <span class="promo-meta-item"><i class="fas fa-percent"></i> Кешбэк баллами</span>
                    </div>

                    <div class="promo-card-footer">
                        <span class="promo-code">CASHBACK10</span>
                        <a href="#" class="promo-btn">Подробнее <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Товары по акции -->
        <div class="promo-products-section">
            <h2>
                <i class="fas fa-tag"></i> Товары со скидкой
            </h2>
            <div class="promo-products-grid">
                <!-- Карточка товара со скидкой -->
                <div class="product-card">
                    <span class="product-tag" style="background: #ef4444;">-25%</span>
                    <div class="product-image">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <div class="product-category">Ноутбуки</div>
                    <div class="product-title">ASUS ROG Strix G16</div>
                    <div class="product-spec">RTX 4060 / 16GB / 512GB</div>
                    <div class="product-footer">
                        <div>
                            <span class="product-price">₽89 990</span>
                            <span class="old-price">₽119 990</span>
                        </div>
                        <button class="btn-circle"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <!-- Карточка товара со скидкой -->
                <div class="product-card">
                    <span class="product-tag" style="background: #ef4444;">-15%</span>
                    <div class="product-image">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <div class="product-category">Процессоры</div>
                    <div class="product-title">Intel Core i7-13700K</div>
                    <div class="product-spec">16 ядер / 24 потока</div>
                    <div class="product-footer">
                        <div>
                            <span class="product-price">₽32 990</span>
                            <span class="old-price">₽38 990</span>
                        </div>
                        <button class="btn-circle"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <!-- Карточка товара со скидкой -->
                <div class="product-card">
                    <span class="product-tag" style="background: #ef4444;">-30%</span>
                    <div class="product-image">
                        <i class="fas fa-memory"></i>
                    </div>
                    <div class="product-category">SSD</div>
                    <div class="product-title">Samsung 980 Pro 1TB</div>
                    <div class="product-spec">NVMe / 7000 МБ/с</div>
                    <div class="product-footer">
                        <div>
                            <span class="product-price">₽7 990</span>
                            <span class="old-price">₽11 490</span>
                        </div>
                        <button class="btn-circle"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <!-- Карточка товара со скидкой -->
                <div class="product-card">
                    <span class="product-tag" style="background: #ef4444;">-20%</span>
                    <div class="product-image">
                        <i class="fas fa-headphones"></i>
                    </div>
                    <div class="product-category">Наушники</div>
                    <div class="product-title">Sony WH-1000XM4</div>
                    <div class="product-spec">шумоподавление / 30ч</div>
                    <div class="product-footer">
                        <div>
                            <span class="product-price">₽19 990</span>
                            <span class="old-price">₽24 990</span>
                        </div>
                        <button class="btn-circle"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Пагинация -->
        <div class="promo-pagination">
            <a href="#" class="pagination-item active">1</a>
            <a href="#" class="pagination-item">2</a>
            <a href="#" class="pagination-item">3</a>
            <a href="#" class="pagination-item">4</a>
            <a href="#" class="pagination-item next">Далее <i class="fas fa-chevron-right"></i></a>
        </div>
    </div> 
@endsection

@section('scripts')
<script>
    // Фильтры акций
    document.querySelectorAll('.promo-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.promo-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            showPromoToast('Фильтр применен');
        });
    });

    // Таймеры (для демонстрации)
    function updateTimers() {
        // Обновляем таймер в главном баннере
        const timerItems = document.querySelectorAll('.promo-main-timer .timer-value');
        if (timerItems.length >= 4) {
            // Просто для демонстрации уменьшаем секунды
            let seconds = parseInt(timerItems[3].textContent);
            seconds = seconds > 0 ? seconds - 1 : 59;
            timerItems[3].textContent = seconds.toString().padStart(2, '0');
            
            if (seconds === 59) {
                let minutes = parseInt(timerItems[2].textContent);
                minutes = minutes > 0 ? minutes - 1 : 59;
                timerItems[2].textContent = minutes.toString().padStart(2, '0');
            }
        }
    }

    // Запускаем обновление таймеров каждую секунду
    setInterval(updateTimers, 1000);
</script>    
@endsection