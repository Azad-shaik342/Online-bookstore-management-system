CREATE DATABASE IF NOT EXISTS online_bookstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE online_bookstore;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS order_items,orders,cart_items,cart,books,categories,users;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 phone VARCHAR(25) NULL,
 profile_image VARCHAR(255) NULL,
 role ENUM('user','admin') NOT NULL DEFAULT 'user',
 status ENUM('active','blocked') NOT NULL DEFAULT 'active',
 last_login_at DATETIME NULL,
 reset_token_hash CHAR(64) NULL,
 reset_token_expires DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 KEY idx_users_role(role)
) ENGINE=InnoDB;
CREATE TABLE categories (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(80) NOT NULL UNIQUE,
 slug VARCHAR(100) NOT NULL UNIQUE,
 description VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE books (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 category_id INT UNSIGNED NOT NULL,
 title VARCHAR(200) NOT NULL,
 author VARCHAR(150) NOT NULL,
 description TEXT NULL,
 price DECIMAL(10,2) NOT NULL CHECK(price>=0),
 stock INT UNSIGNED NOT NULL DEFAULT 0,
 cover_image VARCHAR(255) NULL,
 is_featured TINYINT(1) NOT NULL DEFAULT 0,
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_books_category FOREIGN KEY(category_id) REFERENCES categories(id),
 KEY idx_books_title(title),
 KEY idx_books_status(status)
) ENGINE=InnoDB;
CREATE TABLE cart (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL UNIQUE,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_cart_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE cart_items (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 cart_id INT UNSIGNED NOT NULL,
 book_id INT UNSIGNED NOT NULL,
 quantity TINYINT UNSIGNED NOT NULL DEFAULT 1,
 UNIQUE KEY uq_cart_book(cart_id,book_id),
 CONSTRAINT fk_ci_cart FOREIGN KEY(cart_id) REFERENCES cart(id) ON DELETE CASCADE,
 CONSTRAINT fk_ci_book FOREIGN KEY(book_id) REFERENCES books(id)
) ENGINE=InnoDB;
CREATE TABLE orders (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 total_amount DECIMAL(10,2) NOT NULL,
 shipping_address VARCHAR(500) NOT NULL,
 phone VARCHAR(25) NOT NULL,
 status ENUM('pending','paid','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_orders_user FOREIGN KEY(user_id) REFERENCES users(id),
 KEY idx_orders_created(created_at),
 KEY idx_orders_status(status)
) ENGINE=InnoDB;
CREATE TABLE order_items (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 order_id INT UNSIGNED NOT NULL,
 book_id INT UNSIGNED NOT NULL,
 quantity SMALLINT UNSIGNED NOT NULL,
 unit_price DECIMAL(10,2) NOT NULL,
 CONSTRAINT fk_oi_order FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,
 CONSTRAINT fk_oi_book FOREIGN KEY(book_id) REFERENCES books(id)
) ENGINE=InnoDB;
INSERT INTO categories(name,slug,description) VALUES
('Fiction','fiction','Novels and imaginative storytelling'),
('Technology','technology','Computing, engineering and digital life'),
('Business','business','Leadership, strategy and entrepreneurship'),
('Personal Growth','personal-growth','Habits, creativity and wellbeing');
INSERT INTO books(category_id,title,author,description,price,stock,is_featured) VALUES
(1,'The Midnight Library','Matt Haig','Between life and death there is a library, and within that library, the shelves go on forever.',399.00,18,1),
(2,'Clean Code','Robert C. Martin','A handbook of agile software craftsmanship and readable code.',650.00,12,1),
(3,'The Psychology of Money','Morgan Housel','Timeless lessons on wealth, greed and happiness.',499.00,20,1),
(4,'Atomic Habits','James Clear','An easy and proven way to build good habits and break bad ones.',450.00,15,1),
(1,'The Alchemist','Paulo Coelho','A journey about listening to your heart and following your dreams.',299.00,10,0),
(2,'The Pragmatic Programmer','David Thomas & Andrew Hunt','Your journey to mastery in software development.',720.00,8,0);
