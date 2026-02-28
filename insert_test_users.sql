-- TravelMate Test Users SQL Script
-- This script inserts test users for different roles
-- All passwords are: Test123!

USE travelmate;

-- Insert Test Users
-- Note: Passwords are hashed using PHP's PASSWORD_DEFAULT (bcrypt)
-- Plain text password for all users: Test123!

INSERT INTO users (first_name, last_name, email, phone, date_of_birth, gender, password, role, created_at) VALUES
-- Traveller Test User
('John', 'Traveller', 'traveller@test.com', '0771234567', '1995-05-15', 'male', '$2y$10$YourHashedPasswordHere1', 'traveller', NOW()),

-- Transporter Test User
('Mike', 'Transport', 'transporter@test.com', '0772345678', '1988-08-20', 'male', '$2y$10$YourHashedPasswordHere2', 'transport', NOW()),

-- Accommodation Provider Test User
('Sarah', 'Accommodation', 'accommodation@test.com', '0773456789', '1992-03-10', 'female', '$2y$10$YourHashedPasswordHere3', 'accommodation', NOW()),

-- Admin Test User
('Admin', 'User', 'admin@test.com', '0774567890', '1990-01-01', 'male', '$2y$10$YourHashedPasswordHere4', 'admin', NOW());
