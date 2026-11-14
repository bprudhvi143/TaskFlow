-- RTIH TaskFlow Seed Data
-- Database: task_flow

USE `task_flow`;

-- Clear existing data if re-seeding
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `tasks`;
TRUNCATE TABLE `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Insert Initial Users
-- Default test password for all seeded users is: Password@123
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@rtih.org', '$2y$10$UGL2zXsOwQyxBubXY6jYSOrjpVLXxv4cGGdDDMNNZQ3XUyGr4ILmq', 'admin', NOW()),
(2, 'Alex Morgan', 'alex@rtih.org', '$2y$10$UGL2zXsOwQyxBubXY6jYSOrjpVLXxv4cGGdDDMNNZQ3XUyGr4ILmq', 'user', NOW()),
(3, 'Sarah Jenkins', 'sarah@rtih.org', '$2y$10$UGL2zXsOwQyxBubXY6jYSOrjpVLXxv4cGGdDDMNNZQ3XUyGr4ILmq', 'user', NOW());

-- 2. Insert Initial Tasks for Alex Morgan (user_id = 2)
INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`) VALUES
(1, 2, 'Refactor authentication middleware', 'Audit session security flags and implement session regeneration on login.', 'In Progress', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY), NOW()),
(2, 2, 'Design and review database schema', 'Define InnoDB tables for users and tasks with strict foreign key constraints.', 'Completed', 'Medium', DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY), NOW()),
(3, 2, 'Implement CSRF and session hardening', 'Protect all state-changing POST forms with cryptographically secure tokens.', 'Pending', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY), NOW()),
(4, 2, 'Conduct cross-browser UI testing', 'Verify responsive breakpoints and Material Symbols across Chrome and Edge.', 'Pending', 'Low', DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY), NOW()),
(5, 2, 'Draft technical assignment submission README', 'Document setup steps, architecture, and manual testing results.', 'In Progress', 'Medium', DATE_ADD(CURRENT_DATE(), INTERVAL 3 DAY), NOW());

-- 3. Insert Initial Tasks for Sarah Jenkins (user_id = 3) for multi-user isolation verification
INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`) VALUES
(6, 3, 'Prepare Q4 innovation sprint deliverables', 'Compile quarterly milestone reports and executive summary decks.', 'In Progress', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 5 DAY), NOW()),
(7, 3, 'Audit cloud infrastructure costs', 'Review monthly compute instances and optimize unused database storage.', 'Completed', 'Medium', DATE_SUB(CURRENT_DATE(), INTERVAL 2 DAY), NOW()),
(8, 3, 'Schedule internal team synchronization', 'Coordinate sprint planning meeting with product managers and engineers.', 'Pending', 'Low', DATE_ADD(CURRENT_DATE(), INTERVAL 4 DAY), NOW());
