-- Fix posts table - Add missing image column
ALTER TABLE posts ADD COLUMN IF NOT EXISTS image VARCHAR(500) AFTER description;

-- Or if the above doesn't work, use this:
-- ALTER TABLE posts ADD COLUMN image VARCHAR(500) AFTER description;
