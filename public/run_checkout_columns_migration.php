<?php
/**
 * Migration Script: Add check_out_start and check_out_end columns
 * Run this file once to update your database schema
 */

require_once __DIR__ . '/../config/database.php';

try {
    global $pdo;
    
    echo "Starting migration: Add check_out_start and check_out_end columns...\n";
    
    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/../database/migrations/add_checkout_start_end_columns.sql');
    
    // Execute SQL
    $pdo->exec($sql);
    
    echo "✓ Migration completed successfully!\n";
    echo "The check_out_start and check_out_end columns have been added to the accommodations table.\n";
    
} catch (PDOException $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
