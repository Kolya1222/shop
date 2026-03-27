<?php

return [
    'api' => 2,
    'formid' => 'login',
    'controller' => 'Login',
    'model' => 'Pathologic\EvolutionCMS\MODxAPI\modUsers',
    'loginField' => 'email',
    'rules' => '
{
    "email":{
        "required":"Обязательно введите email",
        "email":"Введите email правильно"
    },
    "password":{
        "required":"Обязательно введите пароль",
        "minLength":{
            "params":6,
            "message":"В пароле должно быть больше 6 символов"
        }
    }
}
',
    'redirectTo' => 18
];
