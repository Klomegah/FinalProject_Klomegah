
-- This SQL file creates the database and tables
-- for the LockIn application


-- Users Table
-- Stores user account information


CREATE TABLE `users` (
    `user_id` INT(11) NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `email` (`email`),
    INDEX `idx_email` (`email`)
) ;


-- Pomodoro Sessions Table
-- Stores completed Pomodoro timer sessions


CREATE TABLE `pomodoro_sessions` (
    `session_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `duration` INT(11) NOT NULL COMMENT 'Duration in seconds',
    `mode` VARCHAR(20) DEFAULT 'focus' COMMENT 'focus, shortBreak, longBreak',
    `completed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`session_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_completed_at` (`completed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Tasks Table
-- Stores tasks associated with Pomodoro sessions


CREATE TABLE  `tasks` (
    `task_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `session_id` INT(11) NULL COMMENT 'Associated Pomodoro session if completed',
    `task_text` TEXT NOT NULL,
    `completed` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`task_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`session_id`) REFERENCES `pomodoro_sessions`(`session_id`) ON DELETE SET NULL,
    
) ;


-- Feynman Notes Table
-- Stores Feynman technique notes from completed sessions


CREATE TABLE  `feynman_notes` (
    `note_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `session_id` INT(11) NOT NULL,
    `initial_explanation` TEXT,
    `simplified_explanation` TEXT,
    `key_concepts` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`note_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`session_id`) REFERENCES `pomodoro_sessions`(`session_id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_session_note` (`session_id`),
    
) ;


-- User Statistics Table
-- Stores user statistics like streaks and total minutes


CREATE TABLE  `user_statistics` (
    `stat_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `total_pomodoros` INT(11) DEFAULT 0,
    `total_minutes` INT(11) DEFAULT 0,
    `current_streak` INT(11) DEFAULT 0,
    `longest_streak` INT(11) DEFAULT 0,
    `last_activity_date` DATE NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`stat_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_user_stat` (`user_id`)
) ;

-- ============================================
-- Table Explanations
-- ============================================
-- 
-- USERS TABLE:
-- - user_id: Primary key, auto-increments
-- - first_name, last_name: User's name (required)
-- - email: Unique email address for login
-- - password_hash: Bcrypt hashed password (never store plain text!)
-- - theme_preference: User's UI theme choice ('light' or 'dark')
-- - created_at: Timestamp when account was created
-- - updated_at: Timestamp when account was last updated
--
-- POMODORO_SESSIONS TABLE:
-- - Stores each completed Pomodoro timer session
-- - Links to user via user_id
-- - Tracks duration and mode (focus/break)
--
-- TASKS TABLE:
-- - Stores tasks that users create
-- - Can be linked to a session when completed
-- - Tracks completion status
--
-- FEYNMAN_NOTES TABLE:
-- - Stores notes created using the Feynman technique
-- - One note per session (unique constraint)
-- - Contains initial explanation, simplified version, and key concepts
--
-- USER_STATISTICS TABLE:
-- - Tracks user progress and achievements
-- - One row per user (unique constraint)
-- - Tracks streaks, total pomodoros, and total minutes
--
-- ============================================

