-- Database (opzionale)
-- CREATE DATABASE IF NOT EXISTS ums CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE ums;

DROP TABLE IF EXISTS users;
CREATE TABLE users
(
    id         BIGINT UNSIGNED                NOT NULL AUTO_INCREMENT,
    username   VARCHAR(60)                    NOT NULL,
    email      VARCHAR(254)                   NOT NULL,
    role_type  ENUM ('user','editor','admin') NOT NULL DEFAULT 'user',
    password   VARCHAR(255)                   NOT NULL, -- password_hash()
    avatar     VARCHAR(255)                   NULL,     -- opzionale
    created_at DATETIME                       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME                       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME                       NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_role (role_type)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Nota: se usi MySQL 8.0.16+ e vuoi evitare ENUM, puoi usare:
-- role_type VARCHAR(16) NOT NULL DEFAULT 'user',
-- CONSTRAINT chk_role_type CHECK (role_type IN ('user','editor','admin'));
