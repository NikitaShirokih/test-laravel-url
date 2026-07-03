# Laravel URL Shortener

![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-v3-FDAE4B?style=flat)
![MySQL](https://img.shields.io/badge/MySQL-8.4-4479A1?style=flat&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-ready-2496ED?style=flat&logo=docker&logoColor=white)

Веб-приложение для создания коротких ссылок, публичного редиректа по короткому коду и просмотра статистики переходов.

---

## Стек

| | |
|---|---|
| Backend | PHP 8.3+, Laravel 13 |
| Панель | Filament v3 |
| База данных | MySQL 8.4 |
| Инфраструктура | Docker, Nginx, PHP-FPM, MySQL |
| Качество кода | Laravel Pint, PHPUnit, Larastan/PHPStan |

---

## Возможности

- Регистрация и вход пользователя
- Создание коротких ссылок
- Редирект по короткому коду
- Фиксация переходов
- Статистика кликов
- IP-адрес и дата перехода
- Управление ссылками через Filament-кабинет

---

## Маршруты

| Назначение | URL |
|---|---|
| Личный кабинет | http://localhost:8080/cabinet |
| Регистрация | http://localhost:8080/cabinet/register |
| Вход | http://localhost:8080/cabinet/login |
| Список ссылок | http://localhost:8080/cabinet/short-links |
| Создание ссылки | http://localhost:8080/cabinet/short-links/create |
| Короткая ссылка | http://localhost:8080/{short_code} |

---

## Запуск

> Требования: Docker и Docker Compose

```bash
git clone https://github.com/NikitaShirokih/test-laravel-url.git
cd test-laravel-url
cp .env.example .env
make init
```

После запуска приложение будет доступно:

```text
http://localhost:8080/cabinet
```

Демо-доступ:

```text
Email: demo@example.com
Password: password
```

## Команды

```bash
make help
```

