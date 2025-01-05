<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Support;

use PDO;
use PDOException;
use WPframework\Terminate;

class DB
{
    protected $wpdb;
    private $host;
    private $databaseName;
    private $username;
    private $password;
    private $table;
    private $tablePrefix;

    public function __construct(
        string $host,
        string $databaseName,
        string $username,
        string $password,
        string $tablePrefix = 'wp_'
    ) {
        $this->host = $host;
        $this->databaseName = $databaseName;
        $this->username = $username;
        $this->password = $password;
        $this->tablePrefix = $tablePrefix;

        // Establish and return a PDO database connection.
        $this->dbConnect();
    }

    /**
     * Fetch all records from the table.
     *
     * @return null|array
     */
    public function all(): ?array
    {
        $records = null;
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->wpdb->prepare($query);

        try {
            $stmt->execute();

            $records = $stmt->fetchAll() ?: null;
        } catch (PDOException $e) {
            Terminate::exit($e);
        }

        return $records;
    }

    /**
     * Find a specific record by ID.
     *
     * @param int $id
     *
     * @return null|array
     */
    public function find($id): ?array
    {
        $record = null;
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt = $this->wpdb->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $stmt->execute();

            $record = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            Terminate::exit($e);
        }

        return $record;
    }

    public function getUser(string $user_login): ?object
    {
        if (! $this->tableExist()) {
            return null;
        }

        $query = 'SELECT * FROM ' . $this->table . ' WHERE user_login = :user_login LIMIT 1';
        $stmt = $this->wpdb->prepare($query);
        $stmt->bindParam(':user_login', $user_login, PDO::PARAM_STR);

        try {
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_OBJ) ?: null;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }

        return $user;
    }

    public function tableExist()
    {
        $qt = $this->wpdb->query("SHOW TABLES LIKE '{$this->table}'");

        return $qt->fetchColumn();
    }

    /**
     * Get records based on a specified condition.
     *
     * @param string $column
     * @param string $value
     *
     * @return null|object
     */
    public function where($column, $value): ?object
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $column . ' = :value';
        $stmt = $this->wpdb->prepare($query);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);

        try {
            $stmt->execute();

            $column = $stmt->fetch(PDO::FETCH_OBJ) ?: null;
        } catch (PDOException $e) {
            Terminate::exit($e);
        }

        return $column;
    }

    /**
     * Retrieve an option value from the wp_options table.
     *
     * @param string $optionName
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($optionName, $default = null)
    {
        $query = "SELECT option_value FROM $this->table WHERE option_name = :option_name LIMIT 1";
        $stmt = $this->wpdb->prepare($query);
        $stmt->bindValue(':option_name', $optionName, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $result = $stmt->fetchColumn();

            if (false === $result) {
                return $default;
            }

            return $this->maybeUnserialize($result);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());

            return $default;
        }
    }

    /**
     * @param string $table_name_no_prefix
     *
     * @return static
     */
    public function table(string $table_name_no_prefix = 'options'): self
    {
        $this->table = $this->tablePrefix . $table_name_no_prefix;

        return $this;
    }

    /**
     * Establish and return a PDO database connection.
     *
     * @return PDO
     */
    private function dbConnect()
    {
        if (\defined('TRY_WITH_NO_DB') && true === \constant('TRY_WITH_NO_DB')) {
            return null;
        }

        if (null !== $this->wpdb) {
            return $this->wpdb;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->databaseName};charset=utf8mb4";

        try {
            $this->wpdb = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            Terminate::exit($e, 503);
        }

        return $this;
    }

    /**
     * Unserialize data if it is serialized.
     *
     * @param string $data The data to potentially unserialize.
     *
     * @return mixed The unserialized data, or the original data if not serialized.
     */
    private function maybeUnserialize($data)
    {
        return $this->isSerialized($data) ? unserialize($data, ['allowed_classes' => false]) : $data;
    }

    /**
     * Check if a given string is serialized.
     *
     * @param string $data The data to check.
     *
     * @return bool True if the data is serialized, false otherwise.
     */
    private function isSerialized($data)
    {
        if (! \is_string($data)) {
            return false;
        }

        $trimmed = trim($data);
        if ('N;' === $trimmed) {
            return true;
        }

        // Basic pattern check to determine if serialized
        if (1 === preg_match('/^(a|O|s|i|b|d):/', $trimmed)) {
            return false !== @unserialize($data, ['allowed_classes' => false]);
        }

        return false;
    }
}
