-- ============================================================
-- Sports Equipment Borrowing System — Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS sports_borrow
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sports_borrow;

-- -----------------------------------------------------------
-- 1. users — ผู้ใช้งานระบบ
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,              -- hashed with password_hash()
    full_name   VARCHAR(100) NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    phone       VARCHAR(20)  DEFAULT NULL,
    role        ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- 2. equipment — อุปกรณ์กีฬา
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS equipment (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    description     TEXT         DEFAULT NULL,
    category        VARCHAR(50)  DEFAULT NULL,       -- e.g. 'ball','racket','net'
    total_quantity  INT UNSIGNED NOT NULL DEFAULT 1,
    available_qty   INT UNSIGNED NOT NULL DEFAULT 1,
    image_url       VARCHAR(255) DEFAULT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- 3. borrow_records — บันทึกการยืม-คืน
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS borrow_records (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    equipment_id    INT UNSIGNED NOT NULL,
    quantity        INT UNSIGNED NOT NULL DEFAULT 1,
    borrow_date     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    due_date        DATETIME     NOT NULL,
    return_date     DATETIME     DEFAULT NULL,
    status          ENUM('borrowed','returned','overdue') NOT NULL DEFAULT 'borrowed',
    note            TEXT         DEFAULT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_borrow_user
        FOREIGN KEY (user_id)      REFERENCES users(id)     ON DELETE CASCADE,
    CONSTRAINT fk_borrow_equipment
        FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- Sample data
-- -----------------------------------------------------------
INSERT INTO users (username, password, full_name, email, phone, role) VALUES
('admin',  '$2y$10$eW5lR3hBbXBsZVBhc3N3b3Jk',  'System Admin',   'admin@example.com',  '0800000001', 'admin'),
('john',   '$2y$10$eW5lR3hBbXBsZVBhc3N3b3Jk',  'John Doe',       'john@example.com',   '0800000002', 'user'),
('jane',   '$2y$10$eW5lR3hBbXBsZVBhc3N3b3Jk',  'Jane Smith',     'jane@example.com',   '0800000003', 'user');

INSERT INTO equipment (name, description, category, total_quantity, available_qty) VALUES
('Football',           'Standard size 5 football',            'ball',    10, 10),
('Basketball',         'Official size 7 basketball',          'ball',     8,  8),
('Badminton Racket',   'Yonex recreational racket',           'racket',  12, 12),
('Tennis Racket',      'Wilson beginner racket',              'racket',   6,  6),
('Volleyball Net',     'Portable outdoor volleyball net',     'net',      3,  3),
('Table Tennis Paddle','Butterfly recreational paddle',       'racket',  10, 10),
('Yoga Mat',           'Non-slip 6 mm yoga mat',             'fitness',  15, 15);

INSERT INTO borrow_records (user_id, equipment_id, quantity, borrow_date, due_date, status) VALUES
(2, 1, 2, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'borrowed'),
(3, 3, 1, NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY), 'borrowed');
