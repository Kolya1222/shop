<?php
return [
    'categories_hit' => [
        'parents'       => 2,
        'depth'         => 0,
        'tvPrefix'      => '',
        'tvList'        => 'popular_categories',
        'returnDLObject'=> 1,
        'filters'       => 'AND(tv:popular_categories:=:1)',
        'orderBy'       => 'createdon DESC'
    ],
    'product' => [
        'parents'       => 2,
        'depth'         => 3,
        'tvPrefix'      => '',
        'tvList'        => 'price, product_tag',
        'returnDLObject'=> 1,
        'filters'       => 'AND(tv:product_tag:=:Новый)',
        'orderBy'       => 'createdon DESC'
    ]
];
