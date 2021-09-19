# Сервис генерации статей на Symfony
Для запуска проекта необходимо:
1. Изменить необходимые параметры в `.env`.
2. Установить зависимости `composer`
```shell
composer install
```
3. Создать БД  
```shell
php bin/console doctrine:database:create
```

[comment]: <> (4. Выполнить миграции)

[comment]: <> (```shell)

[comment]: <> (php bin/console doctrine:fixtures:load)

[comment]: <> (```)
4. Выполнить запуск проекта на локальном сервере командой
```shell
symfony server:start -d
```
---
Сервис будет доступен по адресу `https://127.0.0.1:8000/`
