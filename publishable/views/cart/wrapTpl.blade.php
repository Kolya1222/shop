@if ($data['count'])
    <div class="cart-layout" data-commerce-cart="{{ $data['hash'] }}">
        <div class="cart-items">
            {!! $data['dl.wrap'] !!}
        </div>
        <!-- Боковая панель с оформлением -->
        <div class="cart-sidebar">
            <!-- Итоговая сумма -->
            <div class="cart-summary">
                <h3>Ваш заказ</h3>

                <div class="summary-row">
                    <span>Товары ({{ $data['count'] }} шт.)</span>
                    <span>{{ evo()->runSnippet('PriceFormat', ['price' => $data['items_price']]) }}</span>
                </div>

                <div class="summary-row total">
                    <span>Итого</span>
                    <span>{{ evo()->runSnippet('PriceFormat', ['price' => $data['total']]) }}</span>
                </div>

                <button class="btn-checkout">
                    <i class="fas fa-lock"></i> Оформить заказ
                </button>
            </div>
        </div>
        {!! $data['subtotals'] !!}
    </div>
@else
    <div class="empty-cart">
        <i class="fas fa-shopping-cart"></i>
        <h2>Корзина пуста</h2>
        <p>Но это никогда не поздно исправить :)</p>
        <a href=@makeUrl(2) class="btn-primary"><i class="fas fa-arrow-right"></i> Перейти в каталог</a>
    </div>
@endif
