-- RTIH TaskFlow Web Application - Complete Database Export
-- Schema: task_flow
-- Server version: MySQL 8.0+ / MariaDB 10.4+

CREATE DATABASE IF NOT EXISTS `task_flow` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `task_flow`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_user_email` (`email`),
    INDEX `idx_user_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `tasks`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `status` ENUM('Pending', 'In Progress', 'Completed') NOT NULL DEFAULT 'Pending',
    `priority` ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
    `due_date` DATE NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_tasks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_task_user_id` (`user_id`),
    INDEX `idx_task_status` (`status`),
    INDEX `idx_task_priority` (`priority`),
    INDEX `idx_task_title` (`title`),
    INDEX `idx_task_user_status` (`user_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Seed Data for table `users`
-- Default test password: Password@123
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@rtih.org', '$2y$10$UGL2zXsOwQyxBubXY6jYSOrjpVLXxv4cGGdDDMNNZQ3XUyGr4ILmq', 'admin', NOW()),
(2, 'Alex Morgan', 'alex@rtih.org', '$2y$10$UGL2zXsOwQyxBubXY6jYSOrjpVLXxv4cGGdDDMNNZQ3XUyGr4ILmq', 'user', NOW()),
(3, 'Sarah Jenkins', 'sarah@rtih.org', '$2y$10$UGL2zXsOwQyxBubXY6jYSOrjpVLXxv4cGGdDDMNNZQ3XUyGr4ILmq', 'user', NOW());

-- --------------------------------------------------------
-- Seed Data for table `tasks`
-- --------------------------------------------------------
INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`) VALUES
(1, 2, 'Refactor authentication middleware', 'Audit session security flags and implement session regeneration on login.', 'In Progress', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY), NOW()),
(2, 2, 'Design and review database schema', 'Define InnoDB tables for users and tasks with strict foreign key constraints.', 'Completed', 'Medium', DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY), NOW()),
(3, 2, 'Implement CSRF and session hardening', 'Protect all state-changing POST forms with cryptographically secure tokens.', 'Pending', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY), NOW()),
(4, 2, 'Conduct cross-browser UI testing', 'Verify responsive breakpoints and Material Symbols across Chrome and Edge.', 'Pending', 'Low', DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY), NOW()),
(5, 2, 'Draft technical assignment submission README', 'Document setup steps, architecture, and manual testing results.', 'In Progress', 'Medium', DATE_ADD(CURRENT_DATE(), INTERVAL 3 DAY), NOW()),
(6, 3, 'Prepare Q4 innovation sprint deliverables', 'Compile quarterly milestone reports and executive summary decks.', 'In Progress', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 5 DAY), NOW()),
(7, 3, 'Audit cloud infrastructure costs', 'Review monthly compute instances and optimize unused database storage.', 'Completed', 'Medium', DATE_SUB(CURRENT_DATE(), INTERVAL 2 DAY), NOW()),
(8, 3, 'Schedule internal team synchronization', 'Coordinate sprint planning meeting with product managers and engineers.', 'Pending', 'Low', DATE_ADD(CURRENT_DATE(), INTERVAL 4 DAY), NOW());
