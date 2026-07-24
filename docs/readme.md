# Документация к пакету roilafx/shop

Приведствую всех в этом руководстве будет расмотрен пакет разработанный для Evolution CMS 3.1.30. Представляющий из себя сборку интернет магазина на Commerce с фильтрацией товаров, поиску по сайту и личным кабинетом пользователя с историей заказов.

Ключевой идей этого пакета являлось использование актуальных решений доступных в 3.1.30 и быстрое развертывание для выставления на поток процесса разработки интернет магазинов. То есть серверная часть сайта основывается на контроллере шаблонов TemplateController, а для развертывания используются команды выгрузки и загрузки данных из базы данных.

## Установленные компоненты:

При разработке по возможности старался избегать спорных решений по этому в основе лежит множество уже готовых компонентов. Не все из них используются на данном этапе, но присутствуют в сборке для некоторых типовых сценариев модификации магазина.

1. Commerce
2. Commerce-history
3. AESearch (evoSearch)
4. ClientSettings
5. EditDocs
6. eFilter
7. MultiTV
8. FormSender (Formlister)
9. TemplatesEdit
10. TinyMCE4 (не используется, можно включить в настройках)
11. DocLister
12. Multicategories (не используется)

## Общая структура проекта

Пакет содержит в себе вложенную структуру ориентированную на особенности Evolution CMS. Условно ее можно разделить на 2 слоя. Внешний (Все кроме publishable) и внутрений (Содержимое publishable). Внешний слой отвечает за установку пакета и перенос данных, а внутрений слой отвечает за работоспособность самого сайта.

```
shop/
├── README.md
├── composer.json
├── migrations/
│   ├── 2026_02_25_091555_create_commerce_currency_table.php
│   ├── 2026_02_25_092023_create_commerce_order_statuses_table.php
│   ├── 2026_02_25_092111_create_commerce_orders_table.php
│   ├── 2026_02_25_092151_create_commerce_order_products_table.php
│   ├── 2026_02_25_092207_create_commerce_order_history_table.php
│   ├── 2026_02_25_092254_create_commerce_order_payments_table.php
│   ├── 2026_02_25_092524_create_evosearch_table.php
│   ├── 2026_02_25_092538_create_list_catagory_table.php
│   └── 2026_02_25_092548_create_list_value_table.php
├── publishable/
│   ├── assets/
│   │   ├── import/
│   │   │   └── site_export.json
│   │   ├── js/
│   │   │   └── cropper/
│   │   ├── lib/
│   │   │    ├── Formatter/
│   │   │    ├── Helpers/
│   │   │    ├── MODxAPI/
│   │   │    ├── Module/
│   │   │    ├── SimpleTab/
│   │   │    ├── phpmorphy/
│   │   │    ├── .htaccess
│   │   │    ├── APIHelpers.class.php
│   │   │    └── class.summary.php
│   │   ├── modules/
│   │   │   ├── clientsettings/
│   │   │   ├── eLists/
│   │   │   └── editdocs/
│   │   ├── plugins/
│   │   │   ├── aesearch/
│   │   │   ├── commerce/
│   │   │   ├── evoSearch/
│   │   │   ├── formsender/
│   │   │   ├── multicategories/
│   │   │   ├── templatesedit/
│   │   │   └── tinymce4/
│   │   ├── snippets/
│   │   │    ├── DocLister/
│   │   │    ├── FormLister/
│   │   │    ├── eFilter/
│   │   │    ├── evoSearch/
│   │   │    ├── summary/
│   │   └── tvs/
│   │       ├── multitv/
│   │       └── tovarparams/
│   ├── custom/
│   │   ├── config/
│   │   │   ├── app/
│   │   │   │   └── providers/
│   │   │   │       └── ShopServiceProvider.php
│   │   │   ├── cms/
│   │   │   │   └── settings.php
│   │   │   ├── Cart.php
│   │   │   ├── Commerce.php
│   │   │   ├── Doclister.php
│   │   │   ├── aesearch.php
│   │   │   └── eFilter.php
│   │   ├── forms/
│   │   │    ├── login.php
│   │   │    ├── newsletter.php
│   │   │    ├── order.php
│   │   │    ├── profile.php
│   │   │    └── register.php
│   │   └── packages/
│   │        └── shop/
│   │             ├── plugins/
│   │             │    └── cart.plugin.php
│   │             └── src/
│   │                 ├── BladeDirectives/
│   │                 │   └── PriceFormatDirective.php
│   │                 ├── Controllers/
│   │                 │   ├── .gitignore
│   │                 │   ├── BaseController.php
│   │                 │   ├── CatalogController.php
│   │                 │   ├── PageController.php
│   │                 │   ├── ShopController.php
│   │                 │   └── UserController.php
│   │                 ├── Facades/
│   │                 │   ├── Placeholder.php
│   │                 │   └── Snippet.php
│   │                 ├── Interfaces/
│   │                 │   ├── FilterServiceInterface.php
│   │                 │   └── RunSnippetServiceInterface.php
│   │                 ├── Services/
│   │                 │   ├── PlaceholderService.php
│   │                 │   ├── RunSnippetService.php
│   │                 │   └── eFilterService.php
│   │                 ├── Traits/
│   │                 │   ├── BreadcrumbsTraits.php
│   │                 │   ├── CartTraits.php
│   │                 │   └── DLMenuTraits.php
│   │                 └── ShopServiceProvider.php
│   ├── resources/
│   │   ├── css/
│   │   │   └── main.css
│   │   ├── favicon/
│   │   │   ├── buycolor-16.png
│   │   │   ├── buycolor-32.png
│   │   │   └── buycolor-96.png
│   │   └── fontawesome-free-7.2.0-web/
│   └── views/
│       ├── cart/
│       │   ├── cart_row.blade.php
│       │   ├── cart_wrap.blade.php
│       │   ├── deliveryitem.blade.php
│       │   ├── headercart.blade.php
│       │   ├── paymentitem.blade.php
│       │   ├── report.blade.php
│       │   ├── report_home.blade.php
│       │   └── thanks.blade.php
│       ├── layout/
│       │   └── app.blade.php
│       ├── parts/
│       │   ├── footer.blade.php
│       │   ├── head.blade.php
│       │   ├── header.blade.php
│       │   ├── itemcard.blade.php
│       │   ├── login.blade.php
│       │   ├── newsletter.blade.php
│       │   └── register.blade.php
│       ├── cart.blade.php
│       ├── catalog.blade.php
│       ├── featured.blade.php
│       ├── home.blade.php
│       ├── item.blade.php
│       ├── pagetext.blade.php
│       ├── profile.blade.php
│       └── search_results.blade.php
└── src/
    ├── Console
    │     └── Commands
    │         ├── ExportSiteStructure.php
    │         ├── ImportSiteStructure.php
    │         └── ShopInstall.php
    └── InstallServiceProvider.php
```