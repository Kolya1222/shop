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
        <a href="@makeUrl($data['id'])" class="cart-item-title">{{ $data['pagetitle'] }}</a>
        <!-- Вывод опций товара -->
        @php $totalOptionsPrice = 0; @endphp
        @if (!empty($data['meta']) && is_array($data['meta']))
            <div class="cart-item-options">
                <span class="options-label">Выбранные опции:</span>
                <ul class="options-list">
                    @foreach ($data['meta'] as $option)
                        @php $totalOptionsPrice += $option['price']; @endphp
                        <li class="option-item" data-option-id="{{ $option['id'] }}">
                            <span class="option-name">{{ $option['name'] }}</span>
                            <span class="option-price">(+@price($option['price']))</span>
                            <button class="remove-option-btn" data-commerce-action="remove"
                                data-row="{{ $data['row'] }}" data-remove-option="{{ $option['id'] }}"
                                title="Удалить опцию">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="cart-item-meta">
            <span class="item-count">Количество: {{ $data['count'] }} шт.</span>
            @if (!empty($data['description']))
                <span class="item-description">{{ $data['description'] }}</span>
            @endif
        </div>
    </div>
    <div class="cart-item-actions">
        <button class="cart-item-remove" data-commerce-action="remove" title="Удалить товар">
            <i class="fas fa-trash"></i>
        </button>
        <div class="quantity-selector">
            <span class="quantity-label">Количество:</span>
            <div class="quantity-controls">
                <button type="button" class="quantity-btn" data-commerce-action="decrease"
                    data-row="{{ $data['row'] }}"><i class="fas fa-minus"></i></button>
                <input type="number" name="count" class="quantity-input" min="1" value="{{ $data['count'] }}"
                    data-commerce-action="recount" data-row="{{ $data['row'] }}"
                    oninput="if (this.value < 1) this.value = 1">
                <button class="quantity-btn" type="button" data-commerce-action="increase"
                    data-row="{{ $data['row'] }}"><i class="fas fa-plus"></i></button>
            </div>
        </div>
    </div>
</div>
