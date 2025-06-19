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
