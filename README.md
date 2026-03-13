# Установка
Выполните команды из директории `/core`:

1. Установка пакета
```
php artisan package:installrequire roilafx/shop "*"
```
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
