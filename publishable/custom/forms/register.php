<?php

return [
    'api' => 2,
    'formid' => 'register',
    'controller' => 'Register',
    'userRole' => 4,
    'userGroups' => 'Зарегистрированные',
    'model' => 'Pathologic\EvolutionCMS\MODxAPI\modUsers',
    'rules' => '
{
    "username":{
        "required":"Обязательно введите имя",
        "alphaNumeric":"Только буквы и цифры",
        "custom":{
            "function":"\\FormLister\\Register::uniqueUsername",
            "message":"Имя уже занято"
        }
    },
    "email":{
        "required":"Обязательно введите email",
        "email":"Введите email правильно",
        "custom":{
            "function":"\\FormLister\\Register::uniqueEmail",
            "message":"Этот email уже использует другой пользователь"
        }
    },
    "password":{
        "required":"Обязательно введите пароль",
        "minLength":{
            "params":6,
            "message":"В пароле должно быть больше 6 символов"
        }
    },
    "repeatPassword":{
        "required":"Введите пароль еще раз",
        "equals":{
            "params" : "Этот ключ в описании правила можно не задавать, он будет сформирован контроллером автоматически",
            "message":"Пароли не совпадают"
        }
    }
}
',
    'ccSender' => 1,
    'ccSenderField' => 'email',
    'ccSenderTpl' => '@CODE:Привет [+username.value+].',
    'to' => 'belov.belov-ik@yandex.ru',
    'reportTpl' => '@CODE:Новый пользователь [+username.value+] ([+id.value+])',
    'subject' => 'Регистрация на сайте',
];
