# REST API

## Description
REST API for user management. The project is implemented in PHP using a layered architecture (Controllers, Services, Core, Helpers, Interfaces). Data is stored in MariaDB.

## Quick Start

### 1. Clone the repository
```bash
git clone https://github.com/myupdateavailable-code/rest-api-task.git
cd rest-api-task
```

### 2. Configure connection and start docker to migrate data
- Copy `config/database.php.example` as `config/database.php`. (`database.php` existed in demo)
- Move to `docker/` and run `docker compose up -d`. Credentials in `config/database.php.example` 
is valid for docker connection. No changes needed.


### 3. Install dependencies
```
composer install
composer dumpautoload
```

### 4. Run the application
Entry point: `public/index.php`.

Run `php -S 127.0.0.1:8080 public/index.php` in CLI to start app.

## Project Structure
- `src/Controllers` — controllers

- `src/Core` — application core
- `src/Core/App` — classes which initialize app
- `src/DTO` — DTO for request and auth data
- `src/Helpers` — helper classes
- `src/Interfaces` — interfaces
- `src/Services` — business logic
- `sql/` — SQL scripts for database initialization
- `public/` — public directory (entry point)

## API Endpoints

Endpoints stored in `config/routes.php`.

Short doc for usage:

Endpoints marked with `auth()` require a Bearer token in the Authorization header.

| Method | Endpoint     | Controller & Action         | Auth Required |
|--------|--------------|----------------------------|--------------|
| GET    | `/`          | Welcome::index             | No           |
| GET    | `/users`     | UserController::index      | Yes          |
| GET    | `/users/:id` | UserController::show       | Yes          |
| PUT    | `/users/:id` | UserController::update     | Yes          |
| DELETE | `/users/:id` | UserController::delete     | Yes          |
| POST   | `/login`     | AuthController::login      | No           |
| POST   | `/logout`    | AuthController::login      | Yes          |
| POST   | `/register`  | AuthController::register   | No           |

Confirm login with `Authorization: Bearer <token>` header.
You can use token `d39e201ba4621bcf43589023fe79502e31373539383533313234` if you ran all sql files earlier.
Otherwise, you can log in with any email and password `1234` or register your own user.

**Examples of usage:**

`POST /login`
Login body data as form-data or x-www-form-urlencoded:
`email` and `password`
Returns token in **Authorization** header

`POST /register`
Login body data as form-data or x-www-form-urlencoded:
`email` and `password`.
Returns new user id on success.

`POST /logout`
Logout user by token from **Authorization** header.

`PUT /users/:id`
You can only update your current user.
Expects json body. Request must contain at least 1 field (available fields: `email`, `password`, `age`, `address`).

`
{
    "email": "updated@mail.com",
    "password": "1234",
    "age": "77",
    "address": "Your new address"
}
`

`GET /users`
Returns all users. Only for authorized users.

Also, you can add filer for any attribute.

Example:
`GET /users?age=22&city=lviv`
