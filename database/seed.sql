-- RTIH TaskFlow Seed Data
-- Database: task_flow
-- Updated: November 15, 2026

USE `task_flow`;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `tasks`;
TRUNCATE TABLE `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- 1. Seed Users (Indian / Telugu Names, Popular Email Providers)
-- Seed Password for all accounts: TaskFlow#2026Secure
-- Hash: $2y$10$yU7L14BwZiOSXAzOIbOn.eT8Sgf9rGlEVLEOuzVEGLDsjKx9VMx9e
-- Meets all 5 password rules (Length >= 8, Uppercase, Lowercase, Number, Symbol, No Name)
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Boddeda Prudhvi', 'prudhviboddeda5@gmail.com', '$2y$10$yU7L14BwZiOSXAzOIbOn.eT8Sgf9rGlEVLEOuzVEGLDsjKx9VMx9e', 'admin', NOW()),
(2, 'Kalyan Varma', 'kalyan.varma@gmail.com', '$2y$10$yU7L14BwZiOSXAzOIbOn.eT8Sgf9rGlEVLEOuzVEGLDsjKx9VMx9e', 'user', NOW()),
(3, 'Ananya Rao', 'ananya.rao@gmail.com', '$2y$10$yU7L14BwZiOSXAzOIbOn.eT8Sgf9rGlEVLEOuzVEGLDsjKx9VMx9e', 'user', NOW()),
(4, 'Suresh Reddy', 'suresh.reddy@gmail.com', '$2y$10$yU7L14BwZiOSXAzOIbOn.eT8Sgf9rGlEVLEOuzVEGLDsjKx9VMx9e', 'user', NOW());

-- --------------------------------------------------------
-- 2. Tasks for Administrator Boddeda Prudhvi (user_id = 1)
-- --------------------------------------------------------
INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`) VALUES
(1, 1, 'Review monthly household electricity and internet bills', 'Check billing statements for utility usage and process online payment before due date.', 'In Progress', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY), NOW()),
(2, 1, 'Schedule routine vehicle servicing and oil change', 'Book an appointment with authorized service center for general maintenance and wheel alignment.', 'Pending', 'Medium', DATE_ADD(CURRENT_DATE(), INTERVAL 5 DAY), NOW()),
(3, 1, 'Renew two-wheeler and comprehensive car insurance', 'Compare policy quotes online, download renewal documents, and update insurance binder.', 'Completed', 'High', DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY), NOW()),
(4, 1, 'Organize digital photo album and cloud backups', 'Transfer family vacation photos from phone to external hard drive and verify cloud sync.', 'Pending', 'Low', DATE_ADD(CURRENT_DATE(), INTERVAL 10 DAY), NOW());

-- --------------------------------------------------------
-- 3. Tasks for Kalyan Varma (user_id = 2)
-- --------------------------------------------------------
INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`) VALUES
(5, 2, 'Buy weekly groceries and organic vegetables', 'Purchase fresh produce, pulses, cooking essentials, and milk supplies from local supermarket.', 'In Progress', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY), NOW()),
(6, 2, 'Plan family weekend dinner reservation', 'Call restaurant to reserve a table for six guests on Saturday evening.', 'Completed', 'Medium', DATE_SUB(CURRENT_DATE(), INTERVAL 2 DAY), NOW()),
(7, 2, 'Schedule annual dental checkup appointment', 'Visit clinic for routine dental cleaning and consultation.', 'Pending', 'Medium', DATE_ADD(CURRENT_DATE(), INTERVAL 4 DAY), NOW()),
(8, 2, 'Water indoor balcony plants and trim herbs', 'Ensure soil moisture for potted plants and add organic compost.', 'Completed', 'Low', DATE_SUB(CURRENT_DATE(), INTERVAL 3 DAY), NOW()),
(9, 2, 'Clean and organize workspace desk and cables', 'Sort study materials, clean monitor screen, and manage loose cables with velcro ties.', 'Pending', 'Low', DATE_ADD(CURRENT_DATE(), INTERVAL 3 DAY), NOW());

-- --------------------------------------------------------
-- 4. Tasks for Ananya Rao (user_id = 3)
-- --------------------------------------------------------
INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`) VALUES
(10, 3, 'Prepare monthly budget and track savings', 'Log all utility expenses, grocery receipts, and allocate 20% to emergency savings.', 'In Progress', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 3 DAY), NOW()),
(11, 3, 'Return borrowed books to city central library', 'Check due dates on literature novels and renew library membership card.', 'Completed', 'Low', DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY), NOW()),
(12, 3, 'Order prescription medicines and vitamins refill', 'Order monthly medicine supplies through online pharmacy application.', 'Pending', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY), NOW());

-- --------------------------------------------------------
-- 5. Tasks for Suresh Reddy (user_id = 4)
-- --------------------------------------------------------
INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`) VALUES
(13, 4, 'Fix leaking bathroom faucet and check pipes', 'Replace worn rubber washer on kitchen sink and inspect plumbing connections.', 'In Progress', 'Medium', DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY), NOW()),
(14, 4, 'Book train tickets for festival family trip', 'Check seat availability for festival holidays and book return tickets.', 'Pending', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 6 DAY), NOW()),
(15, 4, 'Submit quarterly society maintenance payment', 'Transfer flat maintenance fee via UPI and save payment receipt copy.', 'Completed', 'Medium', DATE_SUB(CURRENT_DATE(), INTERVAL 4 DAY), NOW());
