<a href=@makeUrl($item['id']) title="{{$item['title']}}" style="text-decoration: none">
<div class="product-card">
    @if ($item['product_tag'])
        <span class="product-tag">{{$item['product_tag']}}</span>
    @endif
    <div class="product-image">
        <i class="fas fa-laptop"></i>
    </div>
    <div class="product-category">{{evo()->getParent($item['id'], 1, 'pagetitle')['pagetitle']}}</div>
    <div class="product-title">{{$item['title']}}</div>
    <div class="product-footer">
        <span class="product-price">{{$item['price']}}</span>
        <button class="btn-circle" data-commerce-action="add" data-id="{{$item['id']}}" data-count="1"><i class="fas fa-plus"></i></button>
    </div>
</div>
</a>