<?php

declare(strict_types=1);

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\SQLite;

use Closure;
use PDO;
use PDOException;
use PDOStatement;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class SQLiteDatabase.
 *
 * A robust SQLite database wrapper. Provides:
 *  - Lazy loading of the PDO connection
 *  - Secure prepared statements
 *  - Transaction helper methods
 *  - Convenience methods for fetching data
 *  - Optionally logs errors and information via a PSR-compatible logger
 *
 * Usage example:
 *
 *  $db = new SQLiteDatabase('/path/to/sqlite.db', $logger);
 *  $rows = $db->fetchAll("SELECT * FROM users WHERE status = :status", ['status' => 'active']);
 */
class SQLiteDatabase
{
    /**
     * @var string
     */
    private string $databasePath;

    /**
     * @var null|LoggerInterface
     */
    private ?LoggerInterface $logger;

    /**
     * @var null|PDO
     */
    private ?PDO $connection = null;

    /**
     * SQLiteDatabase constructor.
     *
     * @param string               $databasePath Path to your SQLite file (e.g., '/path/to/database.db').
     * @param null|LoggerInterface $logger       PSR-compatible logger (optional).
     */
    public function __construct(string $databasePath, ?LoggerInterface $logger = null)
    {
        $this->databasePath = $databasePath;
        $this->logger       = $logger;
    }

    /**
     * Executes a non-SELECT SQL statement (INSERT, UPDATE, DELETE, etc.) and returns affected rows.
     *
     * @param string $query  The SQL query string.
     * @param array  $params Parameters to be bound into the query.
     *
     * @throws PDOException
     *
     * @return int Number of rows affected.
     */
    public function execute(string $query, array $params = []): int
    {
        try {
            $stmt = $this->prepareStatement($query, $params);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $exception) {
            $this->logError(
                'Execute query failed: ' . $exception->getMessage(),
                ['query' => $query, 'params' => $params, 'exception' => $exception]
            );

            throw $exception;
        }
    }

    /**
     * Fetches a single row from a SELECT query.
     *
     * @param string $query  The SQL query string.
     * @param array  $params Parameters to be bound into the query.
     *
     * @throws PDOException
     *
     * @return null|array An associative array of the row, or null if no rows found.
     */
    public function fetchOne(string $query, array $params = []): ?array
    {
        try {
            $stmt   = $this->prepareStatement($query, $params);
            $stmt->execute();
            $result = $stmt->fetch();

            return false === $result ? null : $result;
        } catch (PDOException $exception) {
            $this->logError(
                'Fetch one failed: ' . $exception->getMessage(),
                ['query' => $query, 'params' => $params, 'exception' => $exception]
            );

            throw $exception;
        }
    }

    /**
     * Fetches all rows from a SELECT query.
     *
     * @param string $query  The SQL query string.
     * @param array  $params Parameters to be bound into the query.
     *
     * @throws PDOException
     *
     * @return array An array of associative arrays representing each row.
     */
    public function fetchAll(string $query, array $params = []): array
    {
        try {
            $stmt = $this->prepareStatement($query, $params);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $exception) {
            $this->logError(
                'Fetch all failed: ' . $exception->getMessage(),
                ['query' => $query, 'params' => $params, 'exception' => $exception]
            );

            throw $exception;
        }
    }

    /**
     * Fetches a single column from the first row of a SELECT query.
     *
     * @param string $query       SQL query string.
     * @param array  $params      Parameters to be bound into the query.
     * @param int    $columnIndex Which column to return (0-indexed).
     *
     * @throws PDOException
     *
     * @return null|mixed The value of the column or null if no rows found.
     */
    public function fetchColumn(string $query, array $params = [], int $columnIndex = 0)
    {
        try {
            $stmt = $this->prepareStatement($query, $params);
            $stmt->execute();
            $value = $stmt->fetchColumn($columnIndex);

            return false === $value ? null : $value;
        } catch (PDOException $exception) {
            $this->logError(
                'Fetch column failed: ' . $exception->getMessage(),
                ['query' => $query, 'params' => $params, 'exception' => $exception]
            );

            throw $exception;
        }
    }

    /**
     * Begins a transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function beginTransaction(): bool
    {
        return $this->getConnection()->beginTransaction();
    }

    /**
     * Commits the current transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function commit(): bool
    {
        return $this->getConnection()->commit();
    }

    /**
     * Rolls back the current transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function rollBack(): bool
    {
        return $this->getConnection()->rollBack();
    }

    /**
     * Wraps a closure in a database transaction. Rolls back on exception.
     *
     * Usage:
     *   $db->transaction(function(SQLiteDatabase $db) {
     *       $db->execute("INSERT INTO table ...");
     *       $db->execute("UPDATE table ...");
     *   });
     *
     * @param Closure $callback A function that receives this SQLiteDatabase instance.
     *
     * @throws PDOException Any exception thrown inside the callback triggers a rollback.
     *
     * @return mixed Return whatever the callback returns.
     */
    public function transaction(Closure $callback)
    {
        $this->beginTransaction();

        try {
            $returnValue = $callback($this);
            $this->commit();

            return $returnValue;
        } catch (Throwable $exception) {
            $this->rollBack();
            $this->logError(
                'Transaction failed: ' . $exception->getMessage(),
                ['exception' => $exception]
            );

            throw $exception;
        }
    }

    /**
     * Checks if a table exists in the SQLite database.
     *
     * @param string $tableName Name of the table.
     *
     * @return bool True if the table exists, false otherwise.
     */
    public function tableExists(string $tableName): bool
    {
        $query = "SELECT name FROM sqlite_master WHERE type = 'table' AND name = :table LIMIT 1";
        $result = $this->fetchOne($query, ['table' => $tableName]);

        return null !== $result;
    }

    /**
     * Retrieves the ID of the last inserted row, if any.
     *
     * @return string The last inserted ID.
     */
    public function getLastInsertId(): string
    {
        return $this->getConnection()->lastInsertId();
    }

    /**
     * Closes the database connection if open.
     *
     * In practice, you rarely need to explicitly close the connection;
     * however, this method is provided for completeness.
     */
    public function close(): void
    {
        if (null !== $this->connection) {
            $this->connection = null;
            $this->logInfo('SQLite database connection closed.');
        }
    }

    /**
     * Ensures we have a live PDO connection, lazily connecting if necessary.
     *
     * @throws PDOException If the database file is invalid or the connection fails.
     *
     * @return PDO
     */
    private function getConnection(): PDO
    {
        if (null === $this->connection) {
            // Check for file existence and readability
            if (!is_file($this->databasePath)) {
                $message = "SQLite database file does not exist: {$this->databasePath}";
                $this->logError($message);

                throw new PDOException($message);
            }
            if (!is_readable($this->databasePath)) {
                $message = "SQLite database file is not readable: {$this->databasePath}";
                $this->logError($message);

                throw new PDOException($message);
            }

            // Attempt connection
            try {
                $dsn = \sprintf('sqlite:%s', $this->databasePath);
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                $this->connection = new PDO($dsn, null, null, $options);

                // By default, SQLite needs foreign key checks to be explicitly enabled
                // If you want them on by default:
                // $this->connection->exec('PRAGMA foreign_keys = ON;');

                $this->logInfo("Connected to SQLite database: {$this->databasePath}");
            } catch (PDOException $exception) {
                $this->logError(
                    'Failed to connect to SQLite database: ' . $exception->getMessage(),
                    ['exception' => $exception]
                );

                throw $exception;
            }
        }

        return $this->connection;
    }

    /**
     * Prepares a statement, binds parameters securely, and (optionally) logs.
     *
     * @param string $query  The SQL query with placeholders.
     * @param array  $params Associative or index array of parameters.
     *
     * @throws PDOException
     *
     * @return PDOStatement
     */
    private function prepareStatement(string $query, array $params = []): PDOStatement
    {
        $this->logDebug("Preparing query: {$query}");
        $this->logDebug("Params: " . json_encode($params));

        $stmt = $this->getConnection()->prepare($query);

        foreach ($params as $key => $value) {
            // Determine parameter binding type
            $paramType = PDO::PARAM_STR;
            if (\is_int($value)) {
                $paramType = PDO::PARAM_INT;
            } elseif (\is_bool($value)) {
                $paramType = PDO::PARAM_BOOL;
            } elseif (\is_null($value)) {
                $paramType = PDO::PARAM_NULL;
            }

            // Handle numeric array indexes vs named placeholders
            $placeholder = \is_int($key) ? $key + 1 : ':' . $key;
            $stmt->bindValue($placeholder, $value, $paramType);
        }

        return $stmt;
    }

    /**
     * Logs an informational message using the PSR-3 logger if available.
     *
     * @param string $message
     * @param array  $context
     */
    private function logInfo(string $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->info($message, $context);
        }
    }

    /**
     * Logs a debug-level message using the PSR-3 logger if available.
     *
     * @param string $message
     * @param array  $context
     */
    private function logDebug(string $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->debug($message, $context);
        }
    }

    /**
     * Logs an error-level message using the PSR-3 logger if available.
     *
     * @param string $message
     * @param array  $context
     */
    private function logError(string $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->error($message, $context);
        }
    }
}
