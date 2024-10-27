<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Logger;

use Psr\Log\LoggerInterface;
use RuntimeException;

class Log
{
    private static ?LoggerInterface $logger = null;

    /**
     * Initializes the logger with an optional file path. If no file path is provided,
     * it will fall back to error_log.
     *
     * @param null|LoggerInterface $logger
     */
    public static function init(?LoggerInterface $logger = null): void
    {
        self::$logger = $logger;
    }

    /**
     * Log an emergency message.
     *
     * @param string $message
     * @param array  $context
     */
    public static function emergency(string $message, array $context = []): void
    {
        self::getLogger()->emergency($message, $context);
    }

    /**
     * Log an alert message.
     *
     * @param string $message
     * @param array  $context
     */
    public static function alert(string $message, array $context = []): void
    {
        self::getLogger()->alert($message, $context);
    }

    /**
     * Log a critical message.
     *
     * @param string $message
     * @param array  $context
     */
    public static function critical(string $message, array $context = []): void
    {
        self::getLogger()->critical($message, $context);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     * @param array  $context
     */
    public static function error(string $message, array $context = []): void
    {
        self::getLogger()->error($message, $context);
    }

    /**
     * Log a warning message.
     *
     * @param string $message
     * @param array  $context
     */
    public static function warning(string $message, array $context = []): void
    {
        self::getLogger()->warning($message, $context);
    }

    /**
     * Log a notice message.
     *
     * @param string $message
     * @param array  $context
     */
    public static function notice(string $message, array $context = []): void
    {
        self::getLogger()->notice($message, $context);
    }

    /**
     * Log an informational message.
     *
     * @param string $message
     * @param array  $context
     */
    public static function info(string $message, array $context = []): void
    {
        self::getLogger()->info($message, $context);
    }

    /**
     * Log a debug message.
     *
     * @param string $message
     * @param array  $context
     */
    public static function debug(string $message, array $context = []): void
    {
        self::getLogger()->debug($message, $context);
    }

    /**
     * Ensure that a log file exists. If the file or its parent directories do not exist,
     * this function will create them.
     *
     * @param string $logFile The path to the log file.
     *
     * @throws RuntimeException If the file cannot be created.
     *
     * @return void
     */
    public static function createLogFile(string $logFile): void
    {
        $logDir = \dirname($logFile);

        if ( ! is_dir($logDir)) {
            if ( ! mkdir($logDir, 0777, true) && ! is_dir($logDir)) {
                throw new RuntimeException("Unable to create directory: $logDir");
            }
        }

        if ( ! file_exists($logFile)) {
            if (false === file_put_contents($logFile, '')) {
                throw new RuntimeException("Unable to create log file: $logFile");
            }
        }

        if ( ! is_writable($logFile)) {
            throw new RuntimeException("Log file is not writable: $logFile");
        }
    }

    /**
     * Ensure that the logger is initialized and return the logger instance.
     *
     * @return null|LoggerInterface
     */
    private static function getLogger(): ?LoggerInterface
    {
        return self::$logger;
    }
}
