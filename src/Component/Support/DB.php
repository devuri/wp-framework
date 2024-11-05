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
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $conn;
    private $table;
    private $prefix;

    public function __construct(string $table_name_no_prefix, string $host, string $dbName, string $username, string $password, string $prefix)
    {
        $this->host     = $host;
        $this->dbName   = $dbName;
        $this->username = $username;
        $this->password = $password;
        $this->prefix   = $prefix;

        // set table_name.
        $this->table = $this->prefix . $table_name_no_prefix;
    }

    // Fetch all records from the table
    public function all()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt  = $this->connect()->prepare($query);

        try {
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            Terminate::exit($e);
        }
    }

    // Find a specific record by ID
    public function find($id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt  = $this->connect()->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Terminate::exit($e);
        }
    }

    // Get records based on a specified condition
    /**
     * @psalm-param 'domain' $column
     */
    public function where(string $column, string $value)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $column . ' = :value';
        $stmt  = $this->connect()->prepare($query);
        $stmt->bindParam(':value', $value);

        try {
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            Terminate::exit($e);
        }
    }

    private function connect(): ?PDO
    {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            Terminate::exit($e);
        }

        return $this->conn;
    }
}
