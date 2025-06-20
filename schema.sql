CREATE DATABASE IF NOT EXISTS shop
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_polish_ci;
USE shop;

CREATE TABLE categories (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)     NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(100)     NOT NULL,
  price       DECIMAL(10,2)    NOT NULL,
  quantity    INT              NOT NULL DEFAULT 0,
  description TEXT             NOT NULL,
  category_id INT,
  created_at  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_products_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE product_images (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT              NOT NULL,
  image_path VARCHAR(255)     NOT NULL,
  CONSTRAINT fk_images_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  email      VARCHAR(255)     NOT NULL UNIQUE,
  password   VARCHAR(255)     NOT NULL,
  role       ENUM('user','admin') NOT NULL DEFAULT 'user',
  full_name  VARCHAR(255)     DEFAULT NULL,
  address    VARCHAR(255)     DEFAULT NULL,
  city       VARCHAR(100)     DEFAULT NULL,
  postal_code VARCHAR(20)     DEFAULT NULL,
  country    VARCHAR(100)     DEFAULT NULL,
  created_at TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE orders (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT,
  full_name   VARCHAR(255)     NOT NULL,
  email       VARCHAR(255)     NOT NULL,
  address     VARCHAR(255)     NOT NULL,
  city        VARCHAR(100)     NOT NULL,
  postal_code VARCHAR(20)      NOT NULL,
  country     VARCHAR(100)     NOT NULL,
  total       DECIMAL(10,2)    NOT NULL,
  status      ENUM('new','paid','shipped','closed') NOT NULL DEFAULT 'new',
  created_at  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE order_items (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  order_id   INT              NOT NULL,
  product_id INT              NOT NULL,
  quantity   INT              NOT NULL,
  price      DECIMAL(10,2)    NOT NULL,
  CONSTRAINT fk_items_order
    FOREIGN KEY (order_id) REFERENCES orders(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_items_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE password_resets (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT              NOT NULL,
  token      CHAR(64)         NOT NULL,
  expires_at DATETIME         NOT NULL,
  CONSTRAINT fk_resets_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE product_reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  rating TINYINT NOT NULL,
  comment TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reviews_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
