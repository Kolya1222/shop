<?php
return [
    'snippet' => 'Order',
    'formid'  => 'order',
    'filters' => [
        'name'    => ['trim', 'stripTags', 'removeExtraSpaces'],
        'email'   => ['trim', 'stripTags', 'email', 'removeExtraSpaces'],
        'phone'   => ['trim', 'stripTags', 'phone', 'removeExtraSpaces'],
    ],
    'rules'   => [
        'name'  => [
            'required' => 'Введите имя',
        ],
        'email' => [
            'required' => 'Введите email',
            'email'    => 'Введите email правильно'
        ],
        'phone' => [
            'required' => 'Введите номер телефона',
            'phone'    => 'Введите номер телефона правильно'
        ],
        'delivery_method' => [
            'required' => 'Выберите способ доставки'
        ],
        'payment_method'  => [
            'required' => 'Выберите способ оплаты'
        ],
    ],
    'redirectTo'    => 17
];