<?php
/**
 * Database Connection Test
 * Run this file to test database connectivity
 */

// Load config
require_once __DIR__ . '/config/database.php';

echo "=== DATABASE CONNECTION TEST ===\n\n";

echo "Configuration:\n";
echo "- Host: " . DB_HOST . "\n";
echo "- Database: " . DB_NAME . "\n";
echo "- User: " . DB_USER . "\n";
echo "- Password: " . (DB_PASS ? '[SET]' : '[EMPTY]') . "\n\n";

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);

    echo "✅ SUCCESS! Database connection established.\n\n";

    // Test table count
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Database Tables (" . count($tables) . "):\n";
    foreach ($tables as $table) {
        echo "  - " . $table . "\n";
    }

    // Test user count
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_users");
    $userCount = $stmt->fetch()['total'];

    echo "\nUsers in database: " . $userCount . "\n";

    if ($userCount > 0) {
        $stmt = $pdo->query("SELECT nama_lengkap, email, role FROM tb_users LIMIT 5");
        $users = $stmt->fetchAll();

        echo "\nSample users:\n";
        foreach ($users as $user) {
            echo "  - " . $user['nama_lengkap'] . " (" . $user['email'] . ") - " . ucfirst($user['role']) . "\n";
        }
    }

    echo "\n✅ All tests passed!\n";

} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";

    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "Troubleshooting:\n";
        echo "1. Check DB_USER and DB_PASS in config/database.php\n";
        echo "2. Make sure MySQL is running\n";
    } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "Troubleshooting:\n";
        echo "1. Database 'db_pengajuan_kredit' doesn't exist\n";
        echo "2. Import schema.sql first via phpMyAdmin\n";
        echo "3. Or run: CREATE DATABASE db_pengajuan_kredit;\n";
    } else {
        echo "Troubleshooting:\n";
        echo "1. Check MySQL service is running\n";
        echo "2. Verify credentials in config/database.php\n";
    }

    exit(1);
}
