<?php

return [
    'api'       => 2,
    'formid'    => 'newsletter',
    'filters'   => [
        'email' => ['trim', 'stripTags', 'email', 'removeExtraSpaces'],
    ],
    'rules'     => [
        'email' => [
            'required' => 'Введите email',
            'email'    => 'Введите корректный email',
        ],
    ],
    'successTpl' => '@CODE:<div class="alert alert-success">Вы успешно подписались!</div>',
    'reportTpl'  => '@CODE:Email: [+email+]',
];