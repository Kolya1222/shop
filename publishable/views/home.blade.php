@extends('layout.app')

@section('content')
    <div class="container">
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>{{evo()->getConfig('site_name')}}</h1>
                    <p>{{$introtext}}</p>
                    <a href=@makeUrl(2) class="btn-primary"><i class="fas fa-arrow-right"></i> Начать подбор</a>
                    
                    <div class="hero-stats">
                        <div class="stat-item">
                            <h3>{{evo()->getConfig('client_field_age')}}+</h3>
                            <p>лет на рынке</p>
                        </div>
                        <div class="stat-item">
                            <h3>{{evo()->getConfig('client_field_client')}}k+</h3>
                            <p>довольных клиентов</p>
                        </div>
                        <div class="stat-item">
                            <h3>{{evo()->getConfig('client_field_brand')}}+</h3>
                            <p>брендов</p>
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: center; align-items: center;">
                    <i class="fas fa-microchip" style="font-size: 16rem; color: var(--deep-green); opacity: 0.8; max-width: 100%; height: auto;"></i>
                </div>
            </div>
        </section>

        <div class="section-header">
            <h2>Новинки этой недели</h2>
            <a href="@makeUrl(2)?f%5B5%5D%5B%5D=Новый" class="section-link">Смотреть все <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="product-grid">
            @forelse ($products as $item)
                @include('parts.itemcard', $item)
            @empty
                <div>В ближайщее время тут появятся товары</div>
            @endforelse
        </div>

        <div class="section-header" style="margin-top: 40px;">
            <h2>Популярные категории</h2>
            <a href=@makeUrl(2) class="section-link">Все категории <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="categories-grid">
            @forelse ($categories as $item)
            <a href=@makeUrl($item['id']) style="text-decoration: none;" title="{{$item['title']}}">
                <div class="category-item">
                    {{$item['title']}}
                </div>
            </a>
            @empty
            <div>В ближайщее время тут появятся категории</div>
            @endforelse
        </div>
    </div>
@endsection