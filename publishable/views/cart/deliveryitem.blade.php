<label class="delivery-item">
    <input type="radio" name="delivery_method" value="{{ $code }}">
    <span class="delivery-info">
        <span class="delivery-name">{{ $title }}</span>
        <span class="delivery-price">@price($price)</span>
    </span>
    {!! $markup !!}
</label>
