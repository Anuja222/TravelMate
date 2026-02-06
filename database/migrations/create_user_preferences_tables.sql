-- Create user_environments table
CREATE TABLE IF NOT EXISTS user_environments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    environment_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create user_activities table
CREATE TABLE IF NOT EXISTS user_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX idx_user_environments_user_id ON user_environments(user_id);
CREATE INDEX idx_user_activities_user_id ON user_activities(user_id);
