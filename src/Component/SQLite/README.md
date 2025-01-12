# SQLiteDatabase Class

This provides a detailed overview of how to set up and use the **SQLiteDatabase** class. It covers installation, configuration, examples of common usage patterns, transaction handling, logging, and more. The goal is to help you quickly integrate and leverage this database wrapper in your own PHP 7.4+ projects.


## Table of Contents
1. [Introduction](#introduction)  
2. [Requirements](#requirements)  
3. [Installation & Setup](#installation--setup)  
4. [Class Overview](#class-overview)  
5. [Basic Usage](#basic-usage)  
6. [Transaction Handling](#transaction-handling)  
7. [Convenience Methods](#convenience-methods)  
8. [Logging](#logging)  
9. [Error Handling & Exceptions](#error-handling--exceptions)  
10. [Closing Thoughts](#closing-thoughts)  


## Introduction
The **SQLiteDatabase** class is a robust, PSR-compatible wrapper around SQLite’s native PDO driver. It provides:

- **Secure prepared statements** to avoid SQL injection.  
- **Lazy-loaded connection** so you only connect when you actually run queries.  
- **Transaction helpers** like a dedicated `transaction()` method that automatically handles commits and rollbacks.  
- **Convenience methods** (`fetchAll()`, `fetchOne()`, `fetchColumn()`, etc.) for simpler query operations.  
- **Optional PSR-3 logging** (e.g., via Monolog), with info/error/debug levels for deeper insights.  

This guide assumes you have basic knowledge of PHP, Composer, and how to configure a PSR-4 autoloader.


## Requirements
1. **PHP 7.4+** (strict typing is enforced via `declare(strict_types=1)`).  
2. **SQLite PDO Extension** (usually included by default in most PHP distributions).  
3. **PSR-4 Autoloader** if you plan to integrate this class into a larger application.  
4. **(Optional) PSR-3 Logger** for logging, such as [Monolog](https://github.com/Seldaek/monolog).  


## Installation & Setup
1. **Add the Class**  
   Place the `SQLiteDatabase.php` file in your project’s PSR-4 compatible directory. For example, if your namespace is `App\Database`, you might keep the file at `src/Database/SQLiteDatabase.php`.

2. **Composer Autoloading**  
   Update your `composer.json` file accordingly to ensure your classes under `App\Database` are autoloaded. A typical entry might look like:
   ```json
   {
     "autoload": {
       "psr-4": {
         "App\\": "src/"
       }
     }
   }
   ```
   Then run `composer dump-autoload` to refresh the autoloader.

3. **Configure Logger (Optional)**  
   If you want database logging (info, debug, errors), install a PSR-3 logger such as Monolog:
   ```bash
   composer require monolog/monolog
   ```
   Then inject it into the class constructor.


## Class Overview
Below is a high-level outline of the main properties and methods you’ll interact with in `SQLiteDatabase`:

- **Properties**  
  - `$databasePath` – the path to your SQLite database file.  
  - `$logger` – a PSR-3 logger instance (optional).  
  - `$connection` – holds the PDO connection (lazily instantiated).  

- **Methods**  
  1. `getConnection()` – Establishes or returns an existing PDO connection.  
  2. `execute($query, $params = [])` – Executes a non-SELECT statement (INSERT, UPDATE, DELETE). Returns affected rows.  
  3. `fetchOne($query, $params = [])` – Fetches a single row as an associative array.  
  4. `fetchAll($query, $params = [])` – Fetches all rows as an array of associative arrays.  
  5. `fetchColumn($query, $params = [], $columnIndex = 0)` – Fetches a single scalar value (e.g., `COUNT(*)`).  
  6. `transaction(\Closure $callback)` – Wraps operations in a transaction, auto-committing or rolling back.  
  7. `tableExists($tableName)` – Checks if a table exists in the database.  
  8. `getLastInsertId()` – Retrieves the ID of the last inserted row.  
  9. `close()` – Closes the database connection.  


## Basic Usage
A common workflow is to instantiate `SQLiteDatabase` once and then call its query methods:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Database\SQLiteDatabase;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// 1. Create a PSR-3 logger (optional)
$logger = new Logger('my_logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/logs/sqlite.log', Logger::DEBUG));

// 2. Instantiate the SQLiteDatabase class with the path to your SQLite DB
$dbPath = __DIR__ . '/data/my_database.db';
$db = new SQLiteDatabase($dbPath, $logger);

// 3. Execute an INSERT statement
$rowsAffected = $db->execute("
    INSERT INTO users (username, email, status)
    VALUES (:username, :email, :status)
", [
    'username' => 'john_doe',
    'email'    => 'john@example.com',
    'status'   => 'active'
]);
echo "Rows inserted: {$rowsAffected}\n";

// 4. Fetch rows
$allUsers = $db->fetchAll("SELECT * FROM users");
foreach ($allUsers as $user) {
    echo $user['username'] . ' - ' . $user['email'] . PHP_EOL;
}

// 5. Close the connection (optional)
$db->close();
```

**Notes**:  
- The class automatically connects to SQLite the first time you invoke a method that needs the database.  
- If the database file does not exist or is not readable, a `PDOException` will be thrown with a descriptive message.


## Transaction Handling
Transactions let you treat multiple queries as a single unit of work. If something fails, all changes can be rolled back:

### Manual Transaction
```php
$db->beginTransaction();
try {
    $db->execute("UPDATE users SET status = 'inactive' WHERE username = :user", ['user' => 'john_doe']);
    $db->execute("INSERT INTO logs (message) VALUES ('User deactivated: john_doe')");
    $db->commit();
} catch (Throwable $e) {
    $db->rollBack();
    // Log or handle the exception
    throw $e;
}
```

### Using the `transaction()` Wrapper
A simpler approach is to use the `transaction()` method:
```php
$db->transaction(function (SQLiteDatabase $db) {
    $db->execute("UPDATE users SET status = 'inactive' WHERE username = :user", ['user' => 'john_doe']);
    $db->execute("INSERT INTO logs (message) VALUES ('User deactivated: john_doe')");
});
```
Any exception thrown inside this closure will automatically trigger a rollback.


## Convenience Methods
1. **`fetchOne()`**  
   Returns a single row as an associative array or `null` if no result:
   ```php
   $user = $db->fetchOne("SELECT * FROM users WHERE username = :user", ['user' => 'john_doe']);
   if ($user !== null) {
       // ... do something with $user
   }
   ```
2. **`fetchAll()`**  
   Returns all matching rows:
   ```php
   $allActiveUsers = $db->fetchAll("SELECT * FROM users WHERE status = :status", ['status' => 'active']);
   ```
3. **`fetchColumn()`**  
   Retrieves a single scalar value:
   ```php
   $count = $db->fetchColumn("SELECT COUNT(*) FROM users WHERE status = :status", ['status' => 'active']);
   echo "Number of active users: {$count}\n";
   ```
4. **`tableExists()`**  
   Checks whether a table is present:
   ```php
   if ($db->tableExists('users')) {
       // do something if the table exists
   }
   ```
5. **`getLastInsertId()`**  
   Retrieves the last inserted row ID:
   ```php
   $db->execute("INSERT INTO users (username) VALUES ('jane_doe')");
   $lastId = $db->getLastInsertId();
   echo "Last inserted ID: $lastId\n";
   ```


## Logging
- **Info logs**: Connection status, connection closed messages, etc.  
- **Error logs**: Query failures, transaction rollbacks, missing file issues.  
- **Debug logs**: Detailed query strings, bound parameters.  

To enable logging, pass a PSR-3 logger to the constructor. Monolog is the most common choice:
```php
$logger = new Monolog\Logger('my_logger');
$logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/logs/sqlite.log', Monolog\Logger::DEBUG));

$db = new SQLiteDatabase($dbPath, $logger);
```
If no logger is passed, logging calls are simply ignored.


## Error Handling & Exceptions
By default, the class sets `PDO::ATTR_ERRMODE` to `PDO::ERRMODE_EXCEPTION`. Any database error will throw a `PDOException`. You can catch and handle it like any other exception:
```php
try {
    $db->fetchAll("SELECT * FROM nonexistent_table");
} catch (\PDOException $e) {
    // Log or handle the error
    echo "Database error: " . $e->getMessage();
}
```
Additionally, if the SQLite file is missing or unreadable, `PDOException` is thrown with an appropriate message.  

## Closing Thoughts
The **SQLiteDatabase** class is designed to simplify common SQLite operations while providing a secure, PSR-complaint foundation for more advanced usage. By default, it protects against SQL injection through prepared statements, supports full transaction control, and works seamlessly with popular logging libraries.

If you have specific needs—such as enabling PRAGMA statements or using advanced SQLite features—feel free to extend or customize this class. Happy coding!
