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

use PDOException;

/**
 * Class Repository.
 *
 * A lightweight class offering basic CRUD (Create, Read, Update, Delete)
 * Inspired by popular ORM APIs but kept minimal.
 */
class Repository
{
    /**
     * @var SQLiteDatabase
     */
    protected SQLiteDatabase $db;

    /**
     * @var string The table this repository operates on.
     */
    protected string $table;

    /**
     * @var string The primary key column used for lookups.
     */
    protected string $primaryKey;

    /**
     * Repository constructor.
     *
     * @param SQLiteDatabase $db         Instance of our SQLiteDatabase wrapper.
     * @param string         $table      Table name (e.g., 'users').
     * @param string         $primaryKey Primary key column name (default 'id').
     */
    public function __construct(SQLiteDatabase $db, string $table, string $primaryKey = 'id')
    {
        $this->db         = $db;
        $this->table      = $table;
        $this->primaryKey = $primaryKey;
    }

    /**
     * CREATE: Inserts a new record into the table.
     *
     * @param array $data Key-value pairs representing column => value.
     *
     * @throws PDOException if $data is empty or on any PDO failure.
     *
     * @return int The newly inserted row's primary key as an integer.
     */
    public function create(array $data): int
    {
        if (empty($data)) {
            throw new PDOException('Cannot create with empty data array.');
        }

        $columns      = array_keys($data);
        $placeholders = array_map(static fn ($col) => ":$col", $columns);

        $sql = \sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->quoteIdentifier($this->table),
            implode(', ', array_map([$this, 'quoteIdentifier'], $columns)),
            implode(', ', $placeholders)
        );

        $this->db->execute($sql, $data);

        // Return the newly inserted row ID.
        return (int) $this->db->getLastInsertId();
    }

    /**
     * READ: Fetches a single record by its primary key.
     *
     * @param mixed $id The primary key value.
     *
     * @throws PDOException on any PDO failure.
     *
     * @return null|array Associative array of the record, or null if not found.
     */
    public function find($id): ?array
    {
        $sql = \sprintf(
            'SELECT * FROM %s WHERE %s = :id LIMIT 1',
            $this->quoteIdentifier($this->table),
            $this->quoteIdentifier($this->primaryKey)
        );

        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * READ: Fetch a single record by its primary key or fail.
     * Throws an exception if record is not found.
     *
     * @param mixed $id The primary key value.
     *
     * @throws PDOException if record not found or on PDO failure.
     *
     * @return array The found record.
     */
    public function findOrFail($id): array
    {
        $record = $this->find($id);
        if (null === $record) {
            throw new PDOException(\sprintf(
                'No record found in table [%s] with [%s = %s]',
                $this->table,
                $this->primaryKey,
                (string) $id
            ));
        }

        return $record;
    }

    /**
     * READ: Fetches all records from the table.
     *
     * @throws PDOException on any PDO failure.
     *
     * @return array Array of associative arrays for each row.
     */
    public function all(): array
    {
        $sql = \sprintf(
            'SELECT * FROM %s',
            $this->quoteIdentifier($this->table)
        );

        return $this->db->fetchAll($sql);
    }

    /**
     * UPDATE: Updates an existing record by its primary key.
     *
     * @param mixed $id   The primary key value.
     * @param array $data Key-value pairs representing column => new value.
     *
     * @throws PDOException if $data is empty or on any PDO failure.
     *
     * @return int Number of affected rows (usually 1 if successful).
     */
    public function update($id, array $data): int
    {
        if (empty($data)) {
            throw new PDOException('Cannot update with empty data array.');
        }

        // Build the SET clause dynamically
        $setClauses = [];
        foreach ($data as $col => $val) {
            $quotedCol = $this->quoteIdentifier($col);
            $setClauses[] = "$quotedCol = :$col";
        }

        $sql = \sprintf(
            'UPDATE %s SET %s WHERE %s = :id',
            $this->quoteIdentifier($this->table),
            implode(', ', $setClauses),
            $this->quoteIdentifier($this->primaryKey)
        );

        // Merge the ID into the data array for bound parameters
        $data['id'] = $id;

        return $this->db->execute($sql, $data);
    }

    /**
     * DELETE: Removes a record by its primary key.
     *
     * @param mixed $id The primary key value of the record to delete.
     *
     * @throws PDOException on any PDO failure.
     *
     * @return int Number of affected rows (0 if no matching row was found).
     */
    public function delete($id): int
    {
        $sql = \sprintf(
            'DELETE FROM %s WHERE %s = :id',
            $this->quoteIdentifier($this->table),
            $this->quoteIdentifier($this->primaryKey)
        );

        return $this->db->execute($sql, ['id' => $id]);
    }

    /**
     * Quotes identifiers (table/column names) for safe usage in queries.
     * Uses double quotes for SQLite.
     *
     * @param string $identifier
     *
     * @return string
     */
    protected function quoteIdentifier(string $identifier): string
    {
        // In SQLite, double quotes are the canonical way to quote identifiers
        // Example: users -> "users"
        return '"' . str_replace('"', '""', $identifier) . '"';
    }
}
