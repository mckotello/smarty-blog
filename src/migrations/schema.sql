CREATE DATABASE IF NOT EXISTS blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog;

CREATE TABLE categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            description TEXT,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE posts (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       title VARCHAR(255) NOT NULL,
                       description TEXT,
                       content TEXT,
                       image VARCHAR(255),
                       views INT DEFAULT 0,
                       published_at DATETIME NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       INDEX idx_published (published_at),
                       INDEX idx_views (views)
) ENGINE=InnoDB;

CREATE TABLE post_category (
                               post_id INT NOT NULL,
                               category_id INT NOT NULL,
                               PRIMARY KEY (post_id, category_id),
                               FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                               FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;