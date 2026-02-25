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
                <a href=@makeUrl($menuitem['id'])>{{ $menuitem['title'] }}</a>
            @endforeach
        </nav>
        
        <!-- Блок поиска -->
        <div class="search-wrapper aesearch">
            <form class="search-form" action=@makeUrl(46)>
                <input type="text" name="search" class="search-input" 
                       aria-label="Search" aria-describedby="button-addon2" 
                       placeholder="Поиск товаров..." autocomplete="off">
                <button type="submit" class="search-button" id="button-addon2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <div class="header-icons">
            {!! $cartheader !!}
        </div>
    </div>
</header>