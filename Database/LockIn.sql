-- LockIn Database Setup
-- Run this file in phpMyAdmin or import it to create database and all tables

-- Create database if it doesn't exist
CREATE DATABASE lockin;

-- Use the lockin database
USE lockin;

-- 1. USERS TABLE
CREATE TABLE LockIn_users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_activated BOOLEAN DEFAULT FALSE,
    activation_token VARCHAR(255) NULL,
    token_expires_at DATETIME NULL
);

-- 2. TASKS TABLE
CREATE TABLE tasks (
    task_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    task_text TEXT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES LockIn_users(user_id) ON DELETE CASCADE
);

-- 3. POMODORO SESSIONS TABLE
CREATE TABLE  pomodoro_sessions (
    session_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_date DATETIME NOT NULL,
    duration INT NOT NULL COMMENT 'Duration in seconds',
    mode ENUM('pomodoro', 'short-break', 'long-break') DEFAULT 'pomodoro',
    tasks TEXT COMMENT 'Array of task texts (stored as JSON string)',
    completed_tasks TEXT COMMENT 'Array of completed task texts (stored as JSON string)',
    FOREIGN KEY (user_id) REFERENCES LockIn_users(user_id) ON DELETE CASCADE
);

-- 4. FEYNMAN NOTES TABLE
CREATE TABLE  feynman_notes (
    note_id INT PRIMARY KEY AUTO_INCREMENT,
    session_id INT NOT NULL,
    user_id INT NOT NULL,
    initial_explanation TEXT,
    simplified_explanation TEXT,
    key_concepts TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES pomodoro_sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES LockIn_users(user_id) ON DELETE CASCADE
);

-- 5. DRAFTS TABLE
CREATE TABLE drafts (
    draft_id INT PRIMARY KEY AUTO_INCREMENT,
    session_id INT NOT NULL,
    user_id INT NOT NULL,
    draft_data TEXT NOT NULL COMMENT 'Contains: initialExplanation, simplifiedExplanation, keyConcepts (stored as JSON string)',
    FOREIGN KEY (session_id) REFERENCES pomodoro_sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES LockIn_users(user_id) ON DELETE CASCADE
);
