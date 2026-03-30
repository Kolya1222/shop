<?php

return [
    'api'       => 2,
    'formid'    => 'profile',
    'controller'=> 'Profile',
    'model'     => 'Pathologic\EvolutionCMS\MODxAPI\modUsers',
    'rules'     => [
        "username"  => [
            "required"      => "Обязательно введите имя",
            "alphaNumeric"  => "Только буквы и цифры",
            "custom"    => [
                "function"  => "\\FormLister\\Register::uniqueUsername",
                "message"   => "Имя уже занято"
            ]
        ],
        "phone"     => [
            "required"  => "Введите телефон",
            "phone"     => "Номер неверный"
        ],
        "email"     => [
            "required"  => "Обязательно введите email",
            "email"     => "Введите email правильно",
            "custom"    => [
                "function"  => "\\FormLister\\Register::uniqueEmail",
                "message"   => "Этот email уже использует другой пользователь"
            ]
        ],
        "password"  => [
            "minLength" => [
                "params"    => 6,
                "message"   => "В пароле должно быть больше 6 символов"
            ]
        ],
        "repeatPassword"=> [
            "equals"    => [
                "params"    => "Этот ключ в описании правила можно не задавать, он будет сформирован контроллером автоматически",
                "message"   => "Пароли не совпадают"
            ]
        ]
    ],
    'successTpl'=> '@CODE:<div class="content-info-box success"><h4 class="content-small-title"><i class="fas fa-check-circle"></i>Профиль обновлен</h4></div>'
];
