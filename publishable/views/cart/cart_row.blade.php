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
        @if (!empty($data['meta']))
            @php
                $selectedOptions = json_decode($data['meta'][0], true);;
                $totalOptionsPrice = 0;
            @endphp
            @if (!empty($selectedOptions))
                <div class="cart-item-options">
                    <span class="options-label">Выбранные опции:</span>
                    <ul class="options-list">
                        @foreach ($selectedOptions as $index => $option)
                            @php $totalOptionsPrice += $option['price']; @endphp
                            <li class="option-item" data-option-id="{{ $option['id'] }}">
                                <span class="option-name">{{ $option['name'] }}</span>
                                <span class="option-price">(+{{ evo()->runSnippet('PriceFormat', ['price' => $option['price']]) }})</span>
                                <button class="remove-option-btn" data-commerce-action="remove"
                                    data-row="{{ $data['row'] }}" data-remove-option="{{ $option['id'] }}"
                                    data-option-price="{{ $option['price'] }}" title="Удалить опцию">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
        <div class="cart-item-price">
            {{ evo()->runSnippet('PriceFormat', ['price' => $data['price']]) }}
            @if (!empty($selectedOptions) && count($selectedOptions) > 0)
                <small class="price-breakdown">
                    (базовая: {{ evo()->runSnippet('PriceFormat', ['price' => $data['price'] - $totalOptionsPrice]) }}
                    + опции: {{ evo()->runSnippet('PriceFormat', ['price' => $totalOptionsPrice]) }})
                </small>
            @endif
        </div>
    </div>
</div>
