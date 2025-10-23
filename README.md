# ■ Translation API

A Laravel-based Translation Management API designed to handle multilingual content with support for tagging, searching, and exporting translations for frontend applications.
Built with Laravel Sail for a Dockerized local environment and Laravel Sanctum for secure API authentication using tokens.

## ■ Features

-   Manage translations for multiple locales (en, fr, es, jp)
-   Tag translations for context (web, mobile, marketing)
-   Search translations by key, tag, or content
-   Export translations in JSON format for front-end applications
-   API authentication using Laravel Sanctum
-   Seeder command for large-scale data generation

---

## ■ Installation (Laravel Sail)

```bash
# 1. Clone the repository
git clone https://github.com/alanretubis/translations-api.git
cd translation-api

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Start Laravel Sail
./vendor/bin/sail up -d

# 6. Run migrations
./vendor/bin/sail artisan migrate

```

---

## ■ Authentication (Sanctum)

Generate user and API token:

```bash
php artisan tinker
>>> $user = \App\Models\User::factory()->create();
>>> $token = $user->createToken('api-token')->plainTextToken;
```

Use the token in your API client:

```
Accept       : application/json
Authorization: Bearer YOUR_TOKEN_HERE
Content-type : application/json
```

---

## ■ API Endpoints

| Method | Endpoint                 | Description                                         |
| :----- | :----------------------- | :-------------------------------------------------- |
| GET    | /api/translations        | Get paginated list of translations                  |
| GET    | /api/translations/export | Export all translations in JSON format              |
| POST   | /api/translations        | Create a new translation with optional tags         |
| PUT    | /api/translations/{id}   | Update an existing translation and its tags         |
| DELETE | /api/translations/{id}   | Delete a translation                                |
| GET    | /api/translations/search | Search translations by key, locale, tag, or content |
| GET    | /api/tags                | List all available tags                             |
| GET    | /api/locales             | List all available locales                          |

---

## ■ Example Requests (Thunder Client / Postman / Frontend)

### User Registration

**POST /api/auth/register**

```json
{ "name": "John Doe", "email": "johndoe@email.com", "password": "password" }
```

**Response:**

```json
{ "token": "1|u5RYssWb2UkLEzNL2u8iC7a4..." }
```

### Login

**POST /api/auth/login**

```json
{ "email": "johndoe@email.com", "password": "password" }
```

**Response:**

```json
{ "token": "2|SFZTuIrebIUyH0sE9Hz3fH7..." }
```

### Create Translation

**POST /api/translations**

```json
{
    "locale": "en",
    "key": "welcome.message",
    "value": "Welcome to the Translation API!",
    "tags": ["web", "dashboard"]
}
```

**Response:**

```json
{
    "id": 101,
    "locale": "en",
    "key": "welcome.message",
    "value": "Welcome to the Translation API!",
    "tags": ["web", "dashboard"]
}
```

### Update Translation

**PUT /api/translations/101**

```json
{
    "value": "Welcome to our updated Translation API!",
    "tags": ["web"]
}
```

**Response:**

```json
{
    "id": 101,
    "locale": "en",
    "key": "welcome.message",
    "value": "Welcome to our updated Translation API!",
    "tags": ["web"]
}
```

### Export Translations

**GET /api/translations/export**

**Response:**

```json
{
    "current_page": 1,
    "data": [
        {
            "key": "key.TRauZOG9.0",
            "value": "Sample translation 8uBUDBXKPqEhD9qmk28xawnI8UMIlo",
            "locale": "de",
            "tags": []
        },
        {
            "key": "key.fjK9ZgcW.1",
            "value": "Sample translation yKbzvpUNx18gQOzDOqv2ZeHyo9fHus",
            "locale": "es",
            "tags": []
        },
        ...
    ],
    "first_page_url": "http://localhost:8080/api/translations/export?page=1",
    "from": 1,
    "last_page": 3,
    "last_page_url": "http://localhost:8080/api/translations/export?page=3",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "page": null,
            "active": false
        },
        {
            "url": "http://localhost:8080/api/translations/export?page=1",
            "label": "1",
            "page": 1,
            "active": true
        },
        {
            "url": "http://localhost:8080/api/translations/export?page=2",
            "label": "2",
            "page": 2,
            "active": false
        },
        {
            "url": "http://localhost:8080/api/translations/export?page=3",
            "label": "3",
            "page": 3,
            "active": false
        }
    ],
    "next_page_url": "http://localhost:8080/api/translations/export?page=2",
    "path": "http://localhost:8080/api/translations/export",
    "per_page": 1000,
    "prev_page_url": null,
    "to": 1000,
    "total": 3000
}
```

### Search Translations

**GET /api/translations/search?content=Sample&locale=fr&tag=web**

**Response:**

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 293,
            "key": "key.0VZ7JObD.293",
            "locale_id": 2,
            "value": "Sample translation qahoan8PRbtkS1BtYSrIje4Kq4agbz",
            "meta": "{\"source\":\"seed\"}",
            "created_at": "2025-10-23T10:09:09.000000Z",
            "updated_at": "2025-10-23T10:09:09.000000Z",
            "locale": {
                "id": 2,
                "code": "fr",
                "name": "FRENCH",
                "created_at": "2025-10-23T10:09:03.000000Z",
                "updated_at": "2025-10-23T10:09:03.000000Z"
            },
            "tags": [
                {
                    "id": 2,
                    "name": "desktop",
                    "created_at": "2025-10-23T10:09:04.000000Z",
                    "updated_at": "2025-10-23T10:09:04.000000Z",
                    "pivot": {
                        "translation_id": 293,
                        "tag_id": 2
                    }
                },
                {
                    "id": 3,
                    "name": "web",
                    "created_at": "2025-10-23T10:09:04.000000Z",
                    "updated_at": "2025-10-23T10:09:04.000000Z",
                    "pivot": {
                        "translation_id": 293,
                        "tag_id": 3
                    }
                }
            ]
        }
    ],
    "first_page_url": "http://127.0.0.1:8080/api/translations/search?page=1",
    "from": 1,
    "last_page": 2,
    "last_page_url": "http://127.0.0.1:8080/api/translations/search?page=2",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "page": null,
            "active": false
        },
        {
            "url": "http://127.0.0.1:8080/api/translations/search?page=1",
            "label": "1",
            "page": 1,
            "active": true
        },
        {
            "url": "http://127.0.0.1:8080/api/translations/search?page=2",
            "label": "2",
            "page": 2,
            "active": false
        },
        {
            "url": "http://127.0.0.1:8080/api/translations/search?page=2",
            "label": "Next &raquo;",
            "page": 2,
            "active": false
        }
    ],
    "next_page_url": "http://127.0.0.1:8080/api/translations/search?page=2",
    "path": "http://127.0.0.1:8080/api/translations/search",
    "per_page": 50,
    "prev_page_url": null,
    "to": 50,
    "total": 53
}
```

---

### List All Tags

**GET /api/tags**

**Response:**

```json
{
    "data": [
        { "id": 1, "name": "web" },
        { "id": 2, "name": "mobile" },
        { "id": 3, "name": "desktop" }
    ]
}
```

---

### List All Locales

**GET /api/locales**

**Response:**

```json
{
    "data": [
        { "id": 1, "code": "en", "name": "English" },
        { "id": 2, "code": "fr", "name": "French" },
        { "id": 3, "code": "es", "name": "Spanish" }
    ]
}
```

### Delete Translation

**DELETE /api/translations/{id}**
Response: `204 No Content`

---

## Seeder Command

```bash
php artisan app:seed-translations {count}
```

Example:

```bash
php artisan app:seed-translations 100000
```

---

## Testing

```bash
./vendor/bin/sail test
```

---

## Notes

This project follows a Service Pattern architecture to ensure modularity, maintainability, and scalability.
Each business logic component will be encapsulated within dedicated service classes, allowing the system to grow and adapt without tightly coupling features.

The database is structured to support high scalability, efficient queries, and future expansion.
