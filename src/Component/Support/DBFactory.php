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

namespace WPframework\Support;

class DBFactory
{
    private static $instance = null;

    // always gets new instance
    private $db;

    /**
     * Creates or retrieves the existing DB instance.
     *
     * @param null|string $tableNameNoPrefix Optional table name without prefix.
     *
     * @return DB
     */
    public static function create(?string $tableNameNoPrefix = null): DB
    {
        if (null === self::$instance) {
            self::$instance = new DB(
                (string) env('DB_HOST'),
                (string) env('DB_NAME'),
                (string) env('DB_USER'),
                (string) env('DB_PASSWORD'),
                (string) env('DB_PREFIX')
            );
        }

        if (null !== $tableNameNoPrefix) {
            self::$instance->table($tableNameNoPrefix);
        }

        return self::$instance;
    }

    /**
     * Creates a new instance of the `DB` class for the specified table.
     *
     * This is useful in cases where a fresh instance of the `DB` class
     * is required instead of a singleton.
     *
     * @param string $tableNameNoPrefix The name of the database table without the prefix.
     *
     * @return DB An initialized `DB` instance with the specified table.
     */
    public static function init(string $tableNameNoPrefix): DB
    {
        return (new DB(
            (string) env('DB_HOST'),
            (string) env('DB_NAME'),
            (string) env('DB_USER'),
            (string) env('DB_PASSWORD'),
            (string) env('DB_PREFIX')
        ))->table($tableNameNoPrefix);
    }

    /**
     * Resets the instance.
     *
     * Useful for testing or clearing cached connections.
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }
}
