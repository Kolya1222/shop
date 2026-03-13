<label class="delivery-item">
    <input type="radio" name="delivery_method" value="{{$data['code']}}" {{ $data['checked'] }}>
    <input type="hidden" name="delivery_price_{{$data['code']}}" value="{{$data['price']}}">
    <span class="delivery-info">
        <span class="delivery-name">{{$data['title']}}</span>
        <span class="delivery-price">{{ evo()->runSnippet('PriceFormat', ['price' => $data['price']]) }}</span>
    </span>
    {!! $data['markup'] !!}
</label>