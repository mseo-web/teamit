# ✅ Тестовое задание компании "TeamIT"

- Фреймворк Symfony 6.4
- Doctrine ORM
- PostgreSQL
- Postman

## 📝 Реализовано:

- Валидация входных данных для создания и обновления задач
- CRUD для задач
- Логирование ошибок и исключений
- Для хранения задач использовать реляционную базу данных PostgresSQL
- Ответы API в формате JSON
- Пагинация для запроса всех задач
- Фильтрация задач по статусу
- Аутентификация с использованием JWT
- Простые unit-тесты

---

## ⚙️ Как запустить приложение

- Загрузить код с GitHub
- composer install
- Создать базу данных
- Прописать свои данные для настройки базы данных в файле .env
- Импортировать базу - teamit.backup или teamit.sql (лежит в корне проекта)
---

## ⚙️ Коллекция Postman

- TeamIT.postman_collection.json (лежит в корне проекта)
---

## ⚙️ Тесты

- vendor/bin/phpunit (для запуска всех тестов)
- vendor/bin/phpunit tests/Controller/RegistrationControllerTest.php (для запуска теста регистрации)
- vendor/bin/phpunit tests/Controller/TaskControllerTest.php (для запуска теста задач)
---

## ⚙️ Запуск приложения

- symfony server:start
