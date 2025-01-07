Доп.инфо.

---

*Запуск проекта на nginx (чтобы не терятся)*

1. Запустить `php-cgi.exe -b 127.0.0.1:9000`
2. Запустить `start nginx`

Конфиг nginx.conf - https://pastebin.com/uXqZg9LF

---
*Docker*

1. Запустить `docker-compose up --build`

Первый запуск

2. Открыть контейнер `docker exec -it laravel_app bash`
3. Выполнить миграции `php artisan migrate`

---

*Redis*

1. Запустить redis cервер локально на ПК `sudo service redis-server start`
2. Открыть редис `redis-cli` и вбить `ping` - получить обратно `PONG`

