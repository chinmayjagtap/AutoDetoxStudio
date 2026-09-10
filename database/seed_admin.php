<?php
/**
 * One-time CLI script to create/update an admin login.
 * Usage: php database/seed_admin.php <username> <password>
 * Defaults to admin / admin123 if no args are given.
 */

if (php_sapi_name() !== 'cli') {
    die('Run this from the command line only.');
}

require __DIR__ . '/../config/db.php';

$username = $argv[1] ?? 'admin';
$password = $argv[2] ?? 'admin123';

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    'INSERT INTO admins (username, password_hash) VALUES (:u, :p)
     ON DUPLICATE KEY UPDATE password_hash = :p2'
);
$stmt->execute(['u' => $username, 'p' => $hash, 'p2' => $hash]);

echo "Admin user ready -> username: {$username} / password: {$password}\n";
echo "Change this password after first login.\n";
