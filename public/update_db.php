<?php
$conn = new mysqli('localhost', 'root', '', 'travelmate');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("ALTER TABLE posts ADD COLUMN upvotes INT DEFAULT 0");
$conn->query("ALTER TABLE posts ADD COLUMN downvotes INT DEFAULT 0");

$conn->query("CREATE TABLE IF NOT EXISTS post_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    vote_type ENUM('upvote', 'downvote') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_vote (post_id, user_id)
)");
echo "Done";
?>