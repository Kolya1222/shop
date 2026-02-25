@extends('layout.app')

@section('styles')
<style>
    /* Заголовок страницы блога */
    .blog-header {
        margin: 30px 0 40px;
        text-align: center;
    }

    .blog-header h1 {
        font-size: 3rem;
        color: var(--deep-green);
        margin-bottom: 16px;
    }

    .blog-header p {
        color: var(--light-graphite);
        font-size: 1.2rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Поиск по блогу */
    .blog-search {
        max-width: 500px;
        margin: 0 auto 40px;
        position: relative;
    }

    .blog-search input {
        width: 100%;
        padding: 16px 24px;
        padding-right: 60px;
        border: 1px solid var(--border-light);
        border-radius: 60px;
        font-size: 1rem;
        transition: 0.2s;
        background: var(--white);
    }

    .blog-search input:focus {
        outline: none;
        border-color: var(--fresh-green);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .blog-search button {
        position: absolute;
        right: 6px;
        top: 6px;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--deep-green);
        color: white;
        border: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .blog-search button:hover {
        background: var(--fresh-green);
    }

    /* Категории блога */
    .blog-categories {
        display: flex;
        gap: 12px;
        margin-bottom: 40px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .blog-category-btn {
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
        text-decoration: none;
    }

    .blog-category-btn i {
        color: var(--fresh-green);
    }

    .blog-category-btn:hover {
        border-color: var(--fresh-green);
        color: var(--deep-green);
    }

    .blog-category-btn.active {
        background: var(--deep-green);
        color: white;
        border-color: var(--deep-green);
    }

    .blog-category-btn.active i {
        color: white;
    }

    /* Основная раскладка блога */
    .blog-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 40px;
        margin-bottom: 60px;
    }

    /* Сетка статей */
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }

    /* Карточка статьи */
    .blog-card {
        background: var(--white);
        border: 1px solid var(--border-light);
        border-radius: 32px;
        overflow: hidden;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow);
        border-color: var(--fresh-green);
    }

    .blog-card-image {
        height: 200px;
        background: var(--sage);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--deep-green);
        font-size: 5rem;
        position: relative;
    }

    .blog-card-category {
        position: absolute;
        top: 16px;
        left: 16px;
        background: var(--deep-green);
        color: white;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .blog-card-content {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .blog-card-meta {
        display: flex;
        gap: 16px;
        margin-bottom: 12px;
        color: var(--light-graphite);
        font-size: 0.9rem;
    }

    .blog-card-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .blog-card-meta i {
        color: var(--fresh-green);
        font-size: 0.8rem;
    }

    .blog-card-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--deep-green);
        margin-bottom: 12px;
        line-height: 1.4;
        text-decoration: none;
        transition: color 0.2s;
    }

    .blog-card-title:hover {
        color: var(--fresh-green);
    }

    .blog-card-excerpt {
        color: var(--light-graphite);
        line-height: 1.6;
        margin-bottom: 20px;
        flex: 1;
    }

    .blog-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px dashed var(--border-light);
        padding-top: 20px;
    }

    .blog-card-author {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .author-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--sage);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--deep-green);
        font-size: 1.1rem;
    }

    .author-name {
        font-weight: 500;
        color: var(--deep-green);
    }

    .blog-card-stats {
        display: flex;
        gap: 12px;
        color: var(--light-graphite);
        font-size: 0.9rem;
    }

    .blog-card-stats span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .blog-card-stats i {
        color: var(--fresh-green);
    }

    .blog-read-more {
        color: var(--fresh-green);
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: gap 0.2s;
    }

    .blog-read-more:hover {
        gap: 10px;
    }

    /* Боковая панель */
    .blog-sidebar {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .sidebar-widget {
        background: var(--white);
        border: 1px solid var(--border-light);
        border-radius: 32px;
        padding: 28px;
    }

    .widget-title {
        font-size: 1.2rem;
        color: var(--deep-green);
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .widget-title i {
        color: var(--fresh-green);
    }

    /* Популярные статьи */
    .popular-posts {
        list-style: none;
    }

    .popular-post-item {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px dashed var(--border-light);
    }

    .popular-post-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .popular-post-image {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        background: var(--sage);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--deep-green);
        font-size: 2rem;
        flex-shrink: 0;
    }

    .popular-post-content {
        flex: 1;
    }

    .popular-post-title {
        font-weight: 600;
        color: var(--deep-green);
        margin-bottom: 6px;
        line-height: 1.4;
        font-size: 0.95rem;
        text-decoration: none;
        display: block;
    }

    .popular-post-title:hover {
        color: var(--fresh-green);
    }

    .popular-post-date {
        color: var(--light-graphite);
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Категории в сайдбаре */
    .sidebar-categories {
        list-style: none;
    }

    .sidebar-category-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px dashed var(--border-light);
        cursor: pointer;
        transition: 0.2s;
    }

    .sidebar-category-item:hover {
        color: var(--fresh-green);
        padding-left: 8px;
    }

    .sidebar-category-item.active {
        color: var(--deep-green);
        font-weight: 600;
    }

    .sidebar-category-item .count {
        background: var(--sage);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .sidebar-category-item.active .count {
        background: var(--deep-green);
        color: white;
    }

    /* Теги */
    .sidebar-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .sidebar-tag {
        padding: 6px 14px;
        background: var(--sage);
        border-radius: 30px;
        color: var(--deep-green);
        font-size: 0.85rem;
        text-decoration: none;
        transition: 0.2s;
    }

    .sidebar-tag:hover {
        background: var(--fresh-green);
        color: white;
    }

    /* Архив */
    .sidebar-archive {
        list-style: none;
    }

    .sidebar-archive-item {
        padding: 8px 0;
        color: var(--light-graphite);
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sidebar-archive-item:hover {
        color: var(--fresh-green);
        padding-left: 8px;
    }

    .sidebar-archive-item i {
        color: var(--fresh-green);
        font-size: 0.8rem;
    }

    /* Подписка */
    .sidebar-subscribe {
        text-align: center;
    }

    .sidebar-subscribe p {
        color: var(--light-graphite);
        margin-bottom: 20px;
    }

    .subscribe-form input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-light);
        border-radius: 40px;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .subscribe-form input:focus {
        outline: none;
        border-color: var(--fresh-green);
    }

    .subscribe-form button {
        width: 100%;
        background: var(--deep-green);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }

    .subscribe-form button:hover {
        background: var(--fresh-green);
    }

    /* Пагинация */
    .blog-pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin: 50px 0 30px;
    }

    /* Адаптация */
    @media (max-width: 1024px) {
        .blog-layout {
            grid-template-columns: 1fr;
        }
        
        .blog-sidebar {
            order: -1;
        }
        
        .blog-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .blog-header h1 {
            font-size: 2.2rem;
        }
        
        .blog-grid {
            grid-template-columns: 1fr;
        }
        
        .blog-card-image {
            height: 180px;
        }
        
        .blog-categories {
            overflow-x: auto;
            flex-wrap: nowrap;
            justify-content: flex-start;
            padding-bottom: 8px;
        }
        
        .blog-category-btn {
            white-space: nowrap;
        }
        
        .sidebar-widget {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .blog-header h1 {
            font-size: 1.8rem;
        }
        
        .blog-card-meta {
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .blog-card-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .popular-post-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .popular-post-image {
            width: 100%;
            height: 120px;
        }
    }
</style>
@endsection

@section('content')
    <div class="container">
        <!-- Заголовок страницы -->
        <div class="blog-header">
            <h1>Блог Тестового магазина</h1>
            <p>Новости, обзоры, гайды и советы от экспертов</p>
        </div>

        <!-- Поиск -->
        <div class="blog-search">
            <input type="text" placeholder="Поиск по статьям...">
            <button><i class="fas fa-search"></i></button>
        </div>

        <!-- Категории -->
        <div class="blog-categories">
            <a href="#" class="blog-category-btn active"><i class="fas fa-newspaper"></i> Все статьи</a>
            <a href="#" class="blog-category-btn"><i class="fas fa-laptop"></i> Ноутбуки</a>
            <a href="#" class="blog-category-btn"><i class="fas fa-microchip"></i> Комплектующие</a>
            <a href="#" class="blog-category-btn"><i class="fas fa-keyboard"></i> Периферия</a>
            <a href="#" class="blog-category-btn"><i class="fas fa-gamepad"></i> Игры</a>
            <a href="#" class="blog-category-btn"><i class="fas fa-tag"></i> Акции</a>
            <a href="#" class="blog-category-btn"><i class="fas fa-star"></i> Обзоры</a>
        </div>

        <!-- Основная раскладка -->
        <div class="blog-layout">
            <!-- Сетка статей -->
            <div class="blog-grid">
                <!-- Статья 1 -->
                @include('parts.blogcard')
                @include('parts.blogcard')
                @include('parts.blogcard')
                @include('parts.blogcard')
                @include('parts.blogcard')
                @include('parts.blogcard')
            </div>

            <!-- Боковая панель -->
            <aside class="blog-sidebar">
                <!-- Популярные статьи -->
                <div class="sidebar-widget">
                    <h3 class="widget-title"><i class="fas fa-fire"></i> Популярное</h3>
                    <div class="popular-posts">
                        <div class="popular-post-item">
                            <div class="popular-post-image">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <div class="popular-post-content">
                                <a href="#" class="popular-post-title">AMD Ryzen 9000: все, что нужно знать</a>
                                <div class="popular-post-date"><i class="fas fa-calendar-alt"></i> 12.03.2025</div>
                            </div>
                        </div>

                        <div class="popular-post-item">
                            <div class="popular-post-image">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="popular-post-content">
                                <a href="#" class="popular-post-title">MacBook Air M3 vs MacBook Pro M3</a>
                                <div class="popular-post-date"><i class="fas fa-calendar-alt"></i> 10.03.2025</div>
                            </div>
                        </div>

                        <div class="popular-post-item">
                            <div class="popular-post-image">
                                <i class="fas fa-memory"></i>
                            </div>
                            <div class="popular-post-content">
                                <a href="#" class="popular-post-title">Лучшие SSD 2025: рейтинг</a>
                                <div class="popular-post-date"><i class="fas fa-calendar-alt"></i> 08.03.2025</div>
                            </div>
                        </div>

                        <div class="popular-post-item">
                            <div class="popular-post-image">
                                <i class="fas fa-headphones"></i>
                            </div>
                            <div class="popular-post-content">
                                <a href="#" class="popular-post-title">Топ игровых гарнитур до 10 000 ₽</a>
                                <div class="popular-post-date"><i class="fas fa-calendar-alt"></i> 05.03.2025</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Категории -->
                <div class="sidebar-widget">
                    <h3 class="widget-title"><i class="fas fa-folder"></i> Категории</h3>
                    <ul class="sidebar-categories">
                        <li class="sidebar-category-item active">
                            Все статьи <span class="count">48</span>
                        </li>
                        <li class="sidebar-category-item">
                            Ноутбуки <span class="count">12</span>
                        </li>
                        <li class="sidebar-category-item">
                            Комплектующие <span class="count">10</span>
                        </li>
                        <li class="sidebar-category-item">
                            Периферия <span class="count">8</span>
                        </li>
                        <li class="sidebar-category-item">
                            Игры <span class="count">6</span>
                        </li>
                        <li class="sidebar-category-item">
                            Акции <span class="count">4</span>
                        </li>
                        <li class="sidebar-category-item">
                            Обзоры <span class="count">8</span>
                        </li>
                    </ul>
                </div>

                <!-- Теги -->
                <div class="sidebar-widget">
                    <h3 class="widget-title"><i class="fas fa-tags"></i> Теги</h3>
                    <div class="sidebar-tags">
                        <a href="#" class="sidebar-tag">#игровые ноутбуки</a>
                        <a href="#" class="sidebar-tag">#процессоры</a>
                        <a href="#" class="sidebar-tag">#AMD</a>
                        <a href="#" class="sidebar-tag">#Intel</a>
                        <a href="#" class="sidebar-tag">#RTX 5080</a>
                        <a href="#" class="sidebar-tag">#SSD</a>
                        <a href="#" class="sidebar-tag">#клавиатуры</a>
                        <a href="#" class="sidebar-tag">#мыши</a>
                        <a href="#" class="sidebar-tag">#мониторы</a>
                        <a href="#" class="sidebar-tag">#GTA 6</a>
                        <a href="#" class="sidebar-tag">#скидки</a>
                        <a href="#" class="sidebar-tag">#распродажа</a>
                    </div>
                </div>

                <!-- Архив -->
                <div class="sidebar-widget">
                    <h3 class="widget-title"><i class="fas fa-archive"></i> Архив</h3>
                    <ul class="sidebar-archive">
                        <li class="sidebar-archive-item"><i class="fas fa-chevron-right"></i> Март 2025</li>
                        <li class="sidebar-archive-item"><i class="fas fa-chevron-right"></i> Февраль 2025</li>
                        <li class="sidebar-archive-item"><i class="fas fa-chevron-right"></i> Январь 2025</li>
                        <li class="sidebar-archive-item"><i class="fas fa-chevron-right"></i> Декабрь 2024</li>
                        <li class="sidebar-archive-item"><i class="fas fa-chevron-right"></i> Ноябрь 2024</li>
                    </ul>
                </div>

                <!-- Подписка -->
                <div class="sidebar-widget sidebar-subscribe">
                    <h3 class="widget-title"><i class="fas fa-envelope"></i> Рассылка</h3>
                    <p>Получайте свежие статьи и новости первыми</p>
                    <div class="subscribe-form">
                        <input type="email" placeholder="Ваш email">
                        <button>Подписаться</button>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Пагинация -->
        <div class="blog-pagination">
            <a href="#" class="pagination-item active">1</a>
            <a href="#" class="pagination-item">2</a>
            <a href="#" class="pagination-item">3</a>
            <a href="#" class="pagination-item">4</a>
            <a href="#" class="pagination-item">5</a>
            <span class="pagination-item">...</span>
            <a href="#" class="pagination-item">12</a>
            <a href="#" class="pagination-item next">Далее <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Фильтр по категориям
    document.querySelectorAll('.blog-category-btn, .sidebar-category-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (this.classList.contains('blog-category-btn')) {
                document.querySelectorAll('.blog-category-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            } else {
                document.querySelectorAll('.sidebar-category-item').forEach(cat => cat.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
</script>
@endsection