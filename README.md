# Project Setup

## 1. Install Dependencies

To install project dependencies, run the following command:

```bash
composer install
```

## 2. Migrate the Database

To migrate the database and create necessary tables, run:

```bash
php artisan migrate
```

## 3. First register a user

To register a new user, make a POST request to the following route:

```bash
POST /api/register
```

and save Bearer token in the `Authorization` header.

## 4. Make seeders

To make seeders, run:

```bash
php artisan db:seed
```

## 5. Run the server

To run the server, run:

```bash
php artisan serve
```

