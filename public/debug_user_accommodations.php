<?php
require __DIR__ . '/../config/database.php';
session_start();

if (!isset($_SESSION['user'])) {
    echo "Not logged in\n";
    exit;
}

$userId = $_SESSION['user']['id'];
echo "User ID: $userId\n\n";

// Check accommodations
$stmt = $pdo->prepare('SELECT id, title, created_at FROM accommodations WHERE user_id = ? ORDER BY created_at DESC LIMIT 10');
$stmt->execute([$userId]);
$accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Accommodations for this user:\n";
foreach ($accommodations as $acc) {
    echo "  ID: " . $acc['id'] . ", Title: " . $acc['title'] . ", Created: " . $acc['created_at'] . "\n";
    
    // Check images for this accommodation
    $imgStmt = $pdo->prepare('SELECT id, image_path, is_main FROM accommodation_images WHERE accommodation_id = ? ORDER BY id');
    $imgStmt->execute([$acc['id']]);
    $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($images)) {
        echo "    - NO IMAGES\n";
    } else {
        foreach ($images as $img) {
            $main = $img['is_main'] ? 'MAIN' : '    ';
            echo "    - [$main] " . $img['image_path'] . "\n";
        }
    }
}
?>
