<?php
/**
 * Insert Test Users Script
 * This script adds test users for different roles
 * Password for all users: Test123!
 */

require_once __DIR__ . '/config/database.php';

// Test users data
$testUsers = [
    [
        'first_name' => 'John',
        'last_name' => 'Traveller',
        'email' => 'traveller@test.com',
        'phone' => '0771234567',
        'date_of_birth' => '1995-05-15',
        'gender' => 'male',
        'password' => 'Test123!',
        'role' => 'traveller'
    ],
    [
        'first_name' => 'Mike',
        'last_name' => 'Transport',
        'email' => 'transporter@test.com',
        'phone' => '0772345678',
        'date_of_birth' => '1988-08-20',
        'gender' => 'male',
        'password' => 'Test123!',
        'role' => 'transport'
    ],
    [
        'first_name' => 'Sarah',
        'last_name' => 'Accommodation',
        'email' => 'accommodation@test.com',
        'phone' => '0773456789',
        'date_of_birth' => '1992-03-10',
        'gender' => 'female',
        'password' => 'Test123!',
        'role' => 'accommodation'
    ],
    [
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@test.com',
        'phone' => '0774567890',
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'password' => 'Test123!',
        'role' => 'admin'
    ]
];

try {
    $sql = "INSERT INTO users (first_name, last_name, email, phone, date_of_birth, gender, password, role, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    $insertedCount = 0;
    $skippedCount = 0;
    
    foreach ($testUsers as $user) {
        // Check if email already exists
        $checkSql = "SELECT email FROM users WHERE email = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$user['email']]);
        
        if ($checkStmt->fetch()) {
            echo "⚠️  User {$user['email']} already exists. Skipping...\n";
            $skippedCount++;
            continue;
        }
        
        // Hash the password
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
        
        // Insert user
        $stmt->execute([
            $user['first_name'],
            $user['last_name'],
            $user['email'],
            $user['phone'],
            $user['date_of_birth'],
            $user['gender'],
            $hashedPassword,
            $user['role']
        ]);
        
        echo "✅ Created {$user['role']} user: {$user['email']}\n";
        $insertedCount++;
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ Successfully inserted: {$insertedCount} users\n";
    if ($skippedCount > 0) {
        echo "⚠️  Skipped (already exist): {$skippedCount} users\n";
    }
    echo str_repeat("=", 50) . "\n\n";
    
    echo "Test User Credentials:\n";
    echo str_repeat("-", 50) . "\n";
    foreach ($testUsers as $user) {
        echo "Role: " . strtoupper($user['role']) . "\n";
        echo "Email: {$user['email']}\n";
        echo "Password: {$user['password']}\n";
        echo str_repeat("-", 50) . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
