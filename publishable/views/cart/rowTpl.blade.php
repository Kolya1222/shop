<div class="cart-item" data-commerce-row="{{ $data['row'] }}" data-id="{{ $data['id'] }}">
    <div class="cart-item-image">
        @php
            $images = json_decode($data['product_gallery'], true)['fieldValue'] ?? [];
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
    </div>
    <div class="cart-item-info">
        <a href=@makeUrl($data['id']) class="cart-item-title">{{ $data['pagetitle'] }}</a>
        <span>Количество: {{ $data['count'] }} шт.</span>
        <span>{{ $data['description'] }}</span>
    </div>
    <div class="cart-item-actions">
        <button class="cart-item-remove" data-commerce-action="remove"><i class="fas fa-times"></i></button>
        <div class="cart-item-price">
            {{ evo()->runSnippet('PriceFormat', ['price' => $data['price']]) }}
        </div>
    </div>
</div>
