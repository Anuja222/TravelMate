-- Add status column to posts table for approval workflow
ALTER TABLE posts ADD COLUMN IF NOT EXISTS status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER description;

-- Add index for status column
ALTER TABLE posts ADD INDEX idx_status (status);
