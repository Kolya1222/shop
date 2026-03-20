# Установка
Выполните команды из директории `/core`:

1. Установка пакета
```
php artisan package:installrequire roilafx/shop "*"
```
*Иногда тут бывает ошибка из-за чего нужно выполнить composer dump-autoload чтобы появились миграции и публиковались скрипты*

2. Выполнение миграций
```
php artisan migrate
```
3. Публикация стилей и скриптов
```
php artisan vendor:publish --provider="roilafx\Install\InstallServiceProvider"
```
4. Выполнить импорт данных
```
php artisan site:full-import --all --clear-first
```
5. Отредактировать composer в /core/custom/ добавив загрузку
```
    "autoload": {
        "psr-4": {
            "EvolutionCMS\\Shop\\": "packages/shop/src/"
        }
    }
```
6. Обновить composer 
```
composer dump-autoload
```

# Магазин на Evolution CMS

Сборка интернет-магазина для Evolution CMS

## Возможности

 - Каталог товаров
 - Корзина и оформление заказов
 - Поиск по сайту
 - Фильтрация товаров

### Установленные компоненты

 - Commerce
 - AESearch (evoSearch)
 - ClientSettings
 - EditDocs
 - eFilter
 - MultiTV
 - FormSender (Formlister)
 - Templatesedit
 - TinyMCE4 (не используется нужно включить в настройках)
 - DocLister
 - Multicategories (не используется)

### Стили

Для стилизации настраивается в 2-х местах:
1. resources/css/main.css - глобальные стили используемые на нескольких страницах
2. blade - файл в секции styles

#### Дополнительная стилизация

Для упрощения жизни себе использовались в место фотографий иконки из fontawesome-free-7.2.0-web.

### Логика на клиенте

Весь js находится в blade - файлах в серкции scripts его не много при желании можно вынести в отдельный файл или удалить.

 - cart.blade.php: вызов клиентского обработчика FormSender и приятные бонусы к модальному окну оплаты заказа
 - catalog.blade.php: подключение jquery необходимого для работы формы фильтрации
 - item.blade.php: переключение табов и изменение количества
 - pagetext.blade.php: переключение изображений

## Логика на стороне сервера

### Контроллеры

Используется 4 контроллера:
 - Base: получение элементов используемых на большинстве страниц
 - Catalog: работа с компонентом eFilter
 - Page: универсальная текстовая страница
 - Shop: работа с корзиной

#### BaseController

Получает из traits:
 - Верхнее меню
 - Нижнее меню
 - Информацию для пользователей из ClientSettings
 - Мини корзину
 - Хлебные крошки

#### CatalogController

Работает с сервисом eFilter через интерфейс

#### PageController

Универсальный контроллер по этому пришлось пойти на некоторые компромисы из-за чего он делает следующие:
 1. Получение информации для стартовой страницы (Категории с меткой хит и новые позиции).
 2. Получение данных для страницы Спасибо (Необходиммые плейсхолдеры)

#### ShopController

Предоставляет необходимые для корзины и оплаты данные:
 - Корзину
 - Способы оплаты
 - Методы доставки

### Все остальное

#### Фасад Snippet

Для вызовов сниппетов используется Фасад Snippet

```php
use EvolutionCMS\Shop\Facades\Snippet;
```

На данным момент в нем записаны следующие вызовы (Название отображает вызываемый сниппет):
 - run (Вызов любого сниппета)
 - doclister
 - dlcrumbs
 - cart
 - dlmenu
 - priceformat
 - efilter
 - efilterresult

Для обращения к ним используется следующий синтаксис:

```php
Snippet::run( $name, $params );
Snippet::doclister( $params );
```

#### Кофигурация

Для предоставления параметров к вызову используются конфиги и соответсвующий фасад для них
```php
use Illuminate\Support\Facades\Config;
```

В проекте используется 6 конфигураций:
 - aesearch: то как выглядят быстрые результаты поиска (нужно будет вынести в blade через @VIEW)
 - settings: настройки Evolition CE CMS
 - Cart: параметры для вызова корзины
 - Doclister: все, что вызывается Доклистером (Папка publishable\assets\snippets\DocLister)
 - eFilter: параметры для фильтрации и результатов фильтрации
 - order: оплата без перезагрузки страницы через FormSender

Форматирование цены происходит во view для этого была добавлена директива
```php
@price($price, $convert)
```

### Плагин Commerce

Кастомизация логики Commerce осуществляется через плагин в нем добавлены следующие события:

 - OnBeforeCartItemAdding
 - OnBeforeCartItemRemoving
 - OnManagerBeforeOrderRender
 - OnRegisterDelivery
 - OnRegisterPayments
 - OnOrderRawDataChanged

Добавлены 2 "глобальные" переменные (Способы доставки и оплаты):

 - deliveries
 - paymets

Особенности которые стоит знать факт выбора опции хранится в ```$params['item']['options']```, а данные об выбранных опциях записываются в ```$params['item']['meta']``` удаление так же работает по этой логике, если есть информация об удалении опции, то удаляется она иначе удаляется весь товар (Стандартная логика Commerce).