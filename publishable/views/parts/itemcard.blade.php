<a href=@makeUrl($id) title="{{ $title }}" style="text-decoration: none">
    <div class="product-card">
        @if ($product_tag)
            <span class="product-tag">{{ $product_tag }}</span>
        @endif
        <div class="product-image">
            <i class="fas fa-laptop"></i>
        </div>
        <div class="product-category">{{ evo()->getParent($id, 1, 'pagetitle')['pagetitle'] }}</div>
        <div class="product-title">{{ $title }}</div>
        @if ($price)
            <div class="product-footer">
                <span class="product-price">@price($price)</span>
                <button class="btn-circle" data-commerce-action="add" data-id="{{ $id }}" data-count="1"><i
                        class="fas fa-plus"></i></button>
            </div>
        @endif
    </div>
</a>