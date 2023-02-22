# ТЗ
**Сущности**
1. Контракты
2. Сим-карты
3. Пользователи
4. Группы сим-карт

**Отношения**
1. Сим-карта привязана к конкретному контракту, на контракте может быть множество сим-карт.
2. Пользователь-клиент привязан к контракту, пользователь-администратор не привязан.
3. Группа сим-карт привязана к контракту, на контаркте может быть множестко групп.
4. Каждая сим-карта может вхоить в несколько групп.

**Действия**
1. Отображение списка сим-карт своего контракта для клиентов и всех сим-карт для админом. В обоих случаях с поиском по номеру телефона. Для клиентов фильтрация по группам. Для админов фильтрация по контрактам.
2. Отображение списка контрактов и заведение новых контрактов для админов.

Сделать в ларавел для этого миграции, модели и контроллеры для отображения данных.

# Установка

**Установка `backend` пакетов**
```
composer install
```

**Настройка `.env`**
```
php artisan key:generate
```
```
DB_DATABASE=<название базы данных>
DB_USERNAME=<пользователь>
DB_PASSWORD=<пароль>
```

**Подготовака БД**
```
php artisan migrate
php artisan db:seed
```

**Настройка OAuth2.0**
```
php artisan passport:install
```

Должно высветиться примерно следующее:
```
λ php artisan passport:install
Encryption keys generated successfully.
Personal access client created successfully.
Client ID: 1
Client secret: wWonzoytvqyHGjwGuM3hsS5NKA85tm9ehBVR6Qkw
Password grant client created successfully.
Client ID: 2
Client secret: YD08Tx6CPNpjtPQu24FJZtQSJbLyfJUJHKHlhJIn
```

Нас интерисует `Client ID: 2` и секретный ключ для него.
Вписываем эти данные в `.env`:
```
PASSPORT_LOGIN_ENDPOINT="/oauth/token"
PASSPORT_CLIENT_ID=2
PASSPORT_CLIENT_SECRET=YD08Tx6CPNpjtPQu24FJZtQSJbLyfJUJHKHlhJIn
```

Сбрасываем конфигурационный кеш:
```
php artisan config:cache
```

# Postman Shared Test API
[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/7369858-41655e28-3808-4276-992c-ff9b789b3d26?action=collection%2Ffork&collection-url=entityId%3D7369858-41655e28-3808-4276-992c-ff9b789b3d26%26entityType%3Dcollection%26workspaceId%3Da144cf89-462c-4561-a72b-ebfaf69da728#?env%5BENV_APP_LOGIN%5D=W3sia2V5IjoidG9rZW4iLCJ2YWx1ZSI6IiIsImVuYWJsZWQiOnRydWUsInR5cGUiOiJkZWZhdWx0In1d)

После `fork'a` коллекции запросов необходимо выбрать `ENV_APP_LOGIN` окружение в правом верхнем углу `Postman`. Либо создать его самому выбрав `Environments` и добавив запись

| VARIABLE | TYPE | INITIAL VALUE | CURRENT VALUE |
| -------- | ---- | ------------- | ------------- |
| token    | default |  |  |

При использовании запросов в коллекции **ОБЯЗАТЕЛЬНО** выбрать окружение в правом верхнем углу. Именно туда будет записываться токен при отработке запроса `login`.

В каждом запросе кроме `login` проверить наличие во вкладке `Authprization -> Type: OAuth 2.0` значение `Token`, в котором должен быть записан макрос `{{token}}`, что будет подставлять значение `token` из нашего окружения.

# Конструктивные особености
Стандартно `seed'er` генерирует:

| Сущность | Количество |
| -------- | ---------- |
| SIM-карт | 1000 |
| Пользователи | 1000 (2-админа) |
| Контрактов | 8 |

где к каждому контракту привязан:

| Сущность | Количество |
| -------- | ---------- |
| Клиент | 1 |
| SIM-карта или Группа SIM-карт | 1 |

где к `Группа SIM-карт` рандомно привязывается 1-10 `SIM-карт`.

# Доп. помощь

При поиске незанятых клиентов и не админов может помочь след. команда:
```
php artisan find:user --non-client
```

При поиске неиспользуемых SIM-карт поможет след. команда:
```
php artisan find:sim-card --free
```