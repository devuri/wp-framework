<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use WPframework\SQLite\Repository;
use WPframework\SQLite\SQLiteDatabase;

// use WPframework\Logger\FileLogger;

if (!\defined('APP_TEST_PATH')) {
    exit;
}

// 1. Instantiate the SQLiteDatabase
$dbPath = __DIR__ . '/data/my_database.db';
// $db     = new SQLiteDatabase($dbPath, new FileLogger());
$db     = new SQLiteDatabase($dbPath);

// 2. Create a Repository for the "users" table
$usersRepo = new Repository($db, 'users', 'id');

// 3. Create (insert) a new user
$userId = $usersRepo->create([
    'username' => 'john_doe',
    'email'    => 'john@example.com',
    'status'   => 'active',
]);

// 4. Retrieve the user
$user = $usersRepo->find($userId);
if (null !== $user) {
    echo "User found: " . $user['username'] . PHP_EOL;
}

// 4b. Or enforce a match with findOrFail()
try {
    $userOrFail = $usersRepo->findOrFail($userId);
    echo "Found via findOrFail: " . $userOrFail['username'] . PHP_EOL;
} catch (PDOException $e) {
    // handle not found error
    echo $e->getMessage() . PHP_EOL;
}

// 5. Update the user’s status
$rowsAffected = $usersRepo->update($userId, ['status' => 'inactive']);
echo "Rows updated: $rowsAffected" . PHP_EOL;

// 6. Fetch all users
$allUsers = $usersRepo->all();
echo "Total users: " . \count($allUsers) . PHP_EOL;

// 7. Delete the user
$deletedRows = $usersRepo->delete($userId);
echo "Rows deleted: $deletedRows" . PHP_EOL;

// 8. Close the database connection (optional)
$db->close();
