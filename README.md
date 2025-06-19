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

## Updating database schema

The application expects the `users` table to include fields such as `address`, `city`, `postal_code`, and `country`. After you update the codebase, run the schema script again or apply equivalent `ALTER TABLE` commands:

```bash
mysql -u <user> -p < schema.sql
```

Failing to do so can lead to errors like `Unknown column 'address'` when the application tries to access missing fields.
