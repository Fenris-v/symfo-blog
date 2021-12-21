# Сервис генерации статей на Symfony

## Требования:

1. Минимальная версия php 8.1.

## Для запуска проекта необходимо:

1. Склонировать репозиторий командой

```shell
   git clone https://github.com/Fenris-v/symfo-blog.git
```

2. Изменить необходимые параметры в `.env`.
3. Установить зависимости `composer`

```shell
composer install
```

3. Установить зависимости `npm`

```shell
npm install
```

4. Выполнить компиляцию стилей

```shell
npm run build
```

5. Создать БД

```shell
php bin/console doctrine:database:create
```

6. Выполнить миграции

```shell
php bin/console doctrine:migrations:migrate
```

7. Выполнить фикстуры

```shell
php bin/console doctrine:fixtures:load
```

8. Выполнить запуск проекта на локальном сервере командой

```shell
symfony server:start -d
```

---
Сервис будет доступен по адресу [https://127.0.0.1:8000/](https://127.0.0.1:8000/)
