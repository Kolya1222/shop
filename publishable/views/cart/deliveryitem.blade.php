<label class="delivery-item">
    <input type="radio" name="delivery_method" value="{{ $code }}">
    <input type="hidden" name="delivery_price_{{ $code }}" value="{{ $price }}">
    <span class="delivery-info">
        <span class="delivery-name">{{ $title }}</span>
        <span class="delivery-price">@price($price)</span>
    </span>
    {!! $markup !!}
</label>
