<header class="header">
    <div class="container">
        @if (evo()->documentIdentifier === 1)
            <span class="logo">
                <i class="fas fa-cube"></i> @config('site_name')
            </span>
        @else
            <a href="@makeUrl(1)" class="logo">
                <i class="fas fa-cube"></i> @config('site_name')
            </a>
        @endif
        <nav class="nav-links">
            @foreach ($headermenu as $menuitem)
                <a href=@makeUrl($menuitem['id']) title="{{$menuitem['title']}}">{{ $menuitem['title'] }}</a>
            @endforeach
        </nav>

        <!-- Блок поиска -->
        <div class="search-wrapper aesearch">
            <form class="search-form" action=@makeUrl(14)>
                <input type="text" name="search" class="search-input" aria-label="Search"
                    aria-describedby="button-addon2" placeholder="Поиск товаров..." autocomplete="off">
                <button type="submit" title="Поиск" class="search-button" id="button-addon2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="header-icons">
            @guest
                <div class="icon-wrapper" title="Логин" onclick="openLoginModal()">
                    <i class="fas fa-user"></i>
                </div>
                <div class="icon-wrapper" title="Регистрация" onclick="openRegisterModal()">
                    <i class="fas fa-user-plus"></i>
                </div>
            @else
                <div class="user-menu">
                    <a href=@makeUrl(18) title="Личный кабинет"><i class="fas fa-user-circle"></i></a>
                    <a href="?logout" title="Выход" class="logout-link"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            @endguest
            {!! $cartheader !!}
        </div>
    </div>
</header>
