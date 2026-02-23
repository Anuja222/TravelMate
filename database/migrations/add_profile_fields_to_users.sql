-- Add additional profile fields to users table
ALTER TABLE users
ADD COLUMN bio TEXT NULL AFTER gender,
ADD COLUMN country VARCHAR(100) NULL AFTER bio,
ADD COLUMN city VARCHAR(100) NULL AFTER country,
ADD COLUMN timezone VARCHAR(100) NULL AFTER city,
ADD COLUMN travel_style VARCHAR(50) NULL AFTER timezone,
ADD COLUMN budget VARCHAR(50) NULL AFTER travel_style,
ADD COLUMN interests TEXT NULL AFTER budget;
