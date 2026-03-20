<h1>Репорт админу</h1>
<p>Новый заказ #{{ $data['order']['id'] }} на сайте {{ evo()->getConfig('site_url') }}</p>

<h4>Данные покупателя:</h4>


<ul>
    <li>Имя: {{ $data['order']['name'] }}</li>
    <li>Почта: {{ $data['order']['email'] }}</li>
    <li>Телефон: {{ $data['order']['phone'] }}</li>
</ul>
<p>
    Способ доставки: {{ $data['order']['fields']['delivery_method_title'] ?? '' }}<br>
    Способ оплаты: {{ $data['order']['fields']['payment_method_title'] ?? '' }}
</p>

<h4>Состав заказа:</h4>

{!! evo()->runSnippet('Cart', [
    'instance' => 'products',
    'noneWrapOuter' => 1,
    'tvPrefix' => '',
    'ownerTpl' => '@VIEW: cart.cart_wrap',
    'tpl' => '@VIEW: cart.cart_row',
    'tvList' => ['product_gallery'],
    'urlScheme' => 'full',
]) !!}
