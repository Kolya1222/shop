# Конфигурация

Для управления сниппетами и формами было сделано множество файлов конфигураций расположенных в `/custom/config` и `/custom/forms` в этих файлах храняться параметры вызовов для DocLister, Commerce, aesearch, eFilter, FormSender. Для удобства работы с ними используется системный Facades от Laravel. 

Пример работы с конфигурацией:
```php
<?php

namespace EvolutionCMS\Shop\Traits;

use EvolutionCMS\Shop\Facades\Snippet;
use Illuminate\Support\Facades\Config;

trait BreadcrumbsTraits
{
    public function getBreadcrumbs()
    {
        $config = Config::get('Doclister.breadcrumbs');
        $result = Snippet::DLCrumbs($config);
        return ($result);
    }
}
```

## Обзор параметров вызовов

### Системные настройки

`custom/config/cms/settings.php` содержит в себе системные настройки Evolution CMS в данном случае это минимальный файл включающий в себя название, путь к контроллерам управление формированием url

```php
<?php
/**
 * replace system settings
 * $modx->getConfig('site_name');
 */
return [
    'site_name' => 'Тестовый магазин',
    'ControllerNamespace' => 'EvolutionCMS\Shop\Controllers\ ',
    'friendly_url_prefix' => '',
    'friendly_url_suffix' => '',
    'make_folders' => '0'
];
```

### Корзина Cart.php
На сайте представлено 2 корзины. Первая - это мини корзина, которая находится в шапке сайта которая просто отображает количество товаров в корзине. Вторая, уже большая корзина представляет из себя полный набор товаров и элементов управления над ними.

```php
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
```

### Системные переменные магазина Commerce.php
Для работы со страницей Спасибо за заказ в Commerce есть набор системных переменных передаваемых из заказа для вывода на экран полный список можете найти в документации Commerce. 

```php
<?php
return [
    'order_id'      => 'commerce_order.id',
    'order_name'    => 'commerce_order.name',
    'order_phone'   => 'commerce_order.phone',
    'order_email'   => 'commerce_order.email',
    'order_amount'  => 'commerce_order.amount',
    'order_currency'=> 'commerce_order.currency',
    'payment_id'    => 'commerce_payment.id',
    'payment_amount'=> 'commerce_payment.amount',
];
```

### Выборки ресурсов Doclister.php
Для соствление различных выводимых выборок используется DocLister. В данном проекте используется 6 выборок. categories_hit, product, breadcrumbs, headermenu, footermenu, footerclient. Стоит отметить, что тут идет вызов конфига внутри конфига для хлебных крошек.

```php
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
    ],
    'breadcrumbs' => [
        'showCurrent'   => 1,
        'hideMain'      => 0,
        'config'        => 'crumbs:custom'
    ],
    'headermenu' => [
        'parents'       => 0,
        'maxDepth'      => 1,
        'returnDLObject'=> 1,
    ],
    'footermenu' => [
        'parents'       => 2,
        'maxDepth'      => 1,
        'returnDLObject'=> 1,
    ],
    'footerclient' => [
        'parents'       => 9,
        'maxDepth'      => 1,
        'returnDLObject'=> 1,
    ],
];
```

### Поиск по ajax aesearch.php
Вывод 10 товаров подходях под условие поиска. В будущем нужно будет вынести в отдельное @VIEW
```php
<?php
return [
    'display' => 10,
    'tpl' => '@CODE:
        <div class="result-item">
            <a href="[+url+]">[+pagetitle+]</a>
            <p>[+introtext+]</p>
        </div>'
];
```

### Фильтрация товаров eFilter.php
Думаю уже всем известно, что фильтрация у нас состоит из двух сниппетов первый это соствление выборки товаров, а второй это вывод этой выборки.

```php
<?php
return [
    'efilter' => [
        'cfg'               => 'custom',
        'css'               => 0,
        'remove_disabled'   => 0,
        'ajax'              => 1,
    ],
    'efilterresult' => [
        'api'           => 1,
        'tvList'        => 'price, product_tag',
        'tvPrefix'      => '',
        'display'       => 6,
        'depth'         => 4,
        'paginate'      => 'pages',
        'addWhereList'  => 'c.template = 3',
        'config'        => 'paginate:custom'
    ],
];
```

### FormSender 

Все файлы лежащие в папке `/custom/forms` отвечают за работу форм без перезагрузки страниц