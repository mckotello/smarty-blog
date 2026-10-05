# 📝 Blog — PHP + Smarty + MySQL

Простой блог с категориями и постами на чистом PHP 8.1+ без фреймворков.
Шаблонизация — Smarty, БД — MySQL 8, окружение — Docker.

---

## 🚀 Стек

| Компонент     | Версия            |
|---------------|-------------------|
| PHP           | 8.2 (FPM Alpine)  |
| MySQL         | 8.0               |
| Nginx         | alpine            |
| Smarty        | ^4.3              |
| Composer      | latest            |

---

## 📦 Функционал

- **Главная** — список категорий, в которых есть статьи, по 3 последних поста в каждой + кнопка «Все статьи».
- **Страница категории** — название, описание, список статей, сортировка (по дате / по просмотрам), пагинация.
- **Страница статьи** — вся информация + блок из 3 похожих статей (по общим категориям).
- Счётчик просмотров статьи инкрементируется при открытии.
- **Сидинг** — наполнение БД тестовыми категориями и постами.

---

## 📁 Структура проекта

```
blog/
├── docker/
│   ├── nginx/default.conf
│   └── php/Dockerfile
├── src/
│   ├── config/config.php
│   ├── migrations/schema.sql
│   ├── public/
│   │   ├── index.php
│   │   └── assets/
│   │       ├── css/style.css
│   │       └── images/
│   ├── src/
│   │   ├── Database.php
│   │   ├── Models/
│   │   └── Controllers/
│   ├── templates/
│   │   ├── layout.tpl
│   │   ├── home.tpl
│   │   ├── category.tpl
│   │   └── post.tpl
│   ├── seed.php
│   └── composer.json
├── docker-compose.yml
└── README.md
```

---

## ⚙️ Установка и запуск

### Требования

- Docker + Docker Compose
- Git

### Шаги

```bash
# 1. Клонировать репозиторий
git clone <URL_РЕПОЗИТОРИЯ>
cd blog

# 2. Собрать и запустить контейнеры
docker compose up -d --build

# 3. Установить PHP-зависимости (Smarty)
docker compose exec php composer install

# 4. Создать директории для Smarty и дать права
mkdir -p src/var/compile src/var/cache
chmod -R 777 src/var

# 5. Заполнить БД тестовыми данными
docker compose exec php php seed.php
```

После этого сайт доступен по адресу: **http://localhost:8080**

---

## 🗄️ Подключение к БД

| Параметр | Значение |
|----------|----------|
| Host     | `mysql` (внутри docker) / `localhost` (снаружи) |
| Port     | `3306` (внутри) / `3307` (снаружи) |
| Database | `blog`   |
| User     | `blog`   |
| Password | `blog`   |

Схема БД создаётся автоматически при первом старте MySQL из `src/migrations/schema.sql`.

---

## 🛣️ Маршруты

| URL                     | Описание                     |
|-------------------------|------------------------------|
| `/`                     | Главная со всеми категориями |
| `/category/{id}?sort=…` | Страница категории           |
| `/post/{id}`            | Страница статьи              |

Параметры сортировки: `date` (по умолчанию), `views`.

---

## 🧠 Архитектурные решения

- **PDO + prepared statements** — защита от SQL-инъекций.
- **Singleton для PDO** — одно соединение на запрос.
- **Разделение Model / Controller / View** — чистое разделение ответственности.
- **Сортировка через белый список** — имя колонки в `ORDER BY` нельзя параметризовать, поэтому берём только из `['date','views']`.
- **M:N связь** `post ↔ category` через таблицу `post_category`.
- **Похожие статьи** — подзапрос по общим категориям с исключением текущего поста.

---

## 🛠️ Полезные команды

```bash
# Остановить
docker compose down

# Полностью снести (с volume БД)
docker compose down -v

# Логи
docker compose logs -f php

# Пересоздать БД и заново засеять
docker compose down -v && docker compose up -d
docker compose exec php php seed.php
```

---

## 📝 Лицензия

MIT