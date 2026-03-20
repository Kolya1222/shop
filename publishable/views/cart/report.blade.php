<h1>Репорт юзеру</h1>
<p>
    Здравствуйте!<br>
    Вы оставили заказ на сайте @Config('site_url').<br>
    Номер вашего заказа: {{ $data['order']['id'] }}
</p>

<h4>Ваши данные:</h4>
<ul>
    <li>Имя: {{ $data['order']['name'] }}</li>
    <li>Почта: {{ $data['order']['email'] }}</li>
    <li>Телефон: {{ $data['order']['phone'] }}</li>
</ul>
<p>
    Способ доставки: {{ $data['order']['fields']['delivery_method_title'] ?? '' }}<br>
    Способ оплаты: {{ $data['order']['fields']['payment_method_title'] ?? '' }}
</p>

{{ $data['extra'] ?? '' }}

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
