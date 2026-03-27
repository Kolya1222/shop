# Установка
Ставить поверх свежей установки Evolution CMS CE 3.1.30

Выполните команды из директории `/core`:

1. Установка пакета
```
php artisan package:installrequire roilafx/shop "*"
```
*Иногда тут возникает ошибка, в таком случае нужно выполнить `composer dump-autoload`, чтобы появились миграции и можно было публиковались скрипты*

2. Выполнение миграций
```
php artisan migrate
```

3. Публикация стилей и скриптов
```
php artisan vendor:publish --provider="roilafx\Install\InstallServiceProvider"
```

4. Импорт данных
```
php artisan site:full-import --all --clear-first
```

5. Отредактировать `/core/custom/composer.json`, добавив автозагрузку
```json
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

7. Очистить кеш любым удобным вам способом:
- консоль
- административная панель

# Магазин на Evolution CMS CE

## Реализовано

- Каталог и фильтрация товаров
- Корзина и оформление заказов
- Поиск по сайту
- Личный кабинет

### Установленные компоненты

- Commerce
- Commerce-history
- AESearch (evoSearch)
- ClientSettings
- EditDocs
- eFilter
- MultiTV
- FormSender (Formlister)
- TemplatesEdit
- TinyMCE4 (не используется, можно включить в настройках)
- DocLister
- Multicategories (не используется)

## Стили

Стили настраиваются в двух местах:

1. `resources/css/main.css` - глобальные стили, используемые на нескольких страницах
2. Blade-файлы в секции `styles`

### Дополнительная стилизация

Для упрощения разработки вместо фотографий использовались иконки из `fontawesome-free-7.2.0-web`.

## Логика на клиенте

Весь JavaScript находится в Blade-файлах в секции `scripts`. Его немного, при желании можно вынести в отдельный файл или удалить.

- `cart.blade.php`: вызов клиентского обработчика FormSender и дополнительные функции для модального окна оплаты заказа
- `catalog.blade.php`: подключение jQuery, необходимого для работы формы фильтрации
- `item.blade.php`: переключение табов и изменение количества товара
- `pagetext.blade.php`: переключение изображений
- `profile.blade.php`: переключение тобов

## Логика на стороне сервера

### Контроллеры

Используется 4 контроллера:

- **BaseController** - получение элементов, используемых на большинстве страниц
- **CatalogController** - работа с компонентом eFilter
- **PageController** - универсальная текстовая страница
- **ShopController** - работа с корзиной
- **UserControler** - для работы с личным кабинетом

#### BaseController

Получает из traits:

- Верхнее меню
- Нижнее меню
- Информацию для пользователей из `ClientSettings`
- Мини-корзину
- Хлебные крошки
- Если пользователь авторизорован, то получает `Name`, `Email`, `Phone`

#### CatalogController

Работает с сервисом `eFilter` через интерфейс `FilterServiceInterface`.

#### PageController

Универсальный контроллер, выполняющий следующие задачи:

1. Получение информации для стартовой страницы (категории с меткой "хит" и новые позиции)
2. Получение данных для страницы "Спасибо" (необходимые плейсхолдеры)

#### ShopController

Предоставляет необходимые для корзины и оплаты данные:

- Корзину
- Способы оплаты
- Методы доставки

### Фасад Snippet

Для вызовов сниппетов используется фасад `Snippet`:

```php
use EvolutionCMS\Shop\Facades\Snippet;
```

Доступные методы (название соответствует вызываемому сниппету):

- `run($name, $params)` - вызов любого сниппета
- Так же через метод `__call` реализован вызов любого сниппета.

Пример использования:

```php
Snippet::run($name, $params);
Snippet::DLCrumbs($config);
```

### Фасад GetPlaceholder

Для получения плейсхолдеров используется фасад `GetPlaceholder` с методом get:

```php
use EvolutionCMS\Shop\Facades\GetPlaceholder;
```

Пример использования:
```php
return (GetPlaceholder::get($config));
```

### Конфигурация

Для работы с параметрами используются конфиги и соответствующий фасад:

```php
use Illuminate\Support\Facades\Config;
```

В проекте используется следующие конфигураций:

- `aesearch` - внешний вид быстрых результатов поиска (*нужно будет вынести в blade через @VIEW*)
- `settings` - настройки Evolution CMS
- `Cart` - параметры для вызова корзины
- `Commerce` - список плейсхолдеров для сообщения спасибо 
- `Doclister` - параметры для всех вызовов DocLister
- `eFilter` - параметры для фильтрации и результатов фильтрации
- `order` - оплата без перезагрузки страницы через FormSender
- `login` - для контроллера Login из FormLister (используется FormSender)
- `Register` - для контроллера Register из FormLister (используется FormSender)
- `Profile` - для контроллера Profile из FormLister (используется FormSender)

### Форматирование цены

Форматирование цены происходит во view, для этого добавлена директива (*`$convert`- не обязательно*):

```php
@price($price, $convert)
```

### Плагин Commerce

Кастомизация логики Commerce осуществляется через плагин. Добавлены следующие события:

- `OnBeforeCartItemAdding`
- `OnBeforeCartItemRemoving`
- `OnManagerBeforeOrderRender`
- `OnRegisterDelivery`
- `OnRegisterPayments`
- `OnOrderRawDataChanged`

Добавлены две "глобальные" переменные (способы доставки и оплаты):

- `$deliveries`
- `$payments`

**Особенности работы с опциями товара:**

- Факт выбора опции хранится в `$params['item']['options']`
- Данные о выбранных опциях записываются в `$params['item']['meta']`
- Удаление работает по той же логике: если есть информация об удалении опции, удаляется она, иначе удаляется весь товар (стандартная логика Commerce)