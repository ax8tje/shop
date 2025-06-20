# Shop Application

This repository contains a simple PHP based shop. Below you will find instructions for setting up the database used by the application.

## Database setup

The SQL script `schema.sql` creates all tables required by the application. Execute it using a MySQL client:

```bash
mysql -u <user> -p < schema.sql
```

### Schema overview

The script defines the following tables:

- `categories` – product categories
- `products` – items sold in the shop
- `product_images` – images associated with products
- `users` – registered users together with optional address fields
- `orders` – customer orders
- `order_items` – items that belong to an order

See `schema.sql` for the full definition of each table and their columns.

## Configuration

The application reads database connection details from environment variables or an optional
`config.php` file stored outside the `public/` directory. Copy `config.sample.php` to `config.php`
and adjust the values or define the variables below in your server environment:

| Variable    | Description           | Default    |
|-------------|----------------------|------------|
| `DB_HOST`   | Database host        | `localhost` |
| `DB_PORT`   | Port the server uses | `3307`      |
| `DB_NAME`   | Database name        | `shop`      |
| `DB_USER`   | Database user        | `root`      |
| `DB_PASS`   | Database password    | `password`  |
| `DB_CHARSET`| Connection charset   | `utf8mb4`   |

Values missing from either source fall back to the defaults listed above.

## Updating database schema

The application expects the `users` table to include fields such as `address`, `city`, `postal_code`, and `country`. After you update the codebase, run the schema script again or apply equivalent `ALTER TABLE` commands:

```bash
mysql -u <user> -p < schema.sql
```

Failing to do so can lead to errors like `Unknown column 'address'` when the application tries to access missing fields.

## Password reset functionality

Users who forget their password can request a reset link from `forgot_password.php`.
The script stores a hashed token in the `password_resets` table and sends an e-mail
with a link to `reset_password.php`. Tokens expire after one hour. Once the
password is changed the token is removed from the database.

## REST API

The application exposes a simple read-only API for products.

- `GET /api/products.php` returns a list of all products with their `id`, `title` and `price` fields.
- `GET /api/products.php?id=123` returns detailed information about product `123` including the list of image paths.

Responses are provided in JSON format and use standard HTTP status codes.
