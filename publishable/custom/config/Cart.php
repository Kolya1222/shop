<?php
return [
    'global' => [
        'instance'      => 'products',
        'noneWrapOuter' => 1,
        'tvPrefix'      => '',
    ],
    'mini' => [
        'ownerTpl' => '@VIEW: cart.headercart',
    ],
    'cart' => [
        'ownerTpl'  => '@VIEW: cart.cart_wrap',
        'tpl'       => '@VIEW: cart.cart_row',
        'tvList'    => ['product_gallery'],
    ],
];
