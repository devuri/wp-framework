<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Exceptions;

use Exception;
use Throwable;

/**
 * Class HttpException.
 *
 * Custom exception class for HTTP-related errors, extending the base Exception class.
 * Provides additional functionality to handle HTTP status codes and custom error codes.
 */
class HttpException extends Exception
{
    /**
     * @var int HTTP status code associated with the exception.
     */
    protected $statusCode;

    /**
     * @var null|int Custom error code associated with the exception.
     */
    protected $errorCode;

    /**
     * Constructor for the HttpException class.
     *
     * @param string         $message    The exception message.
     * @param int            $statusCode HTTP status code (default is 500).
     * @param null|int       $errorCode  Custom error code for application-specific error handling (optional).
     * @param null|Throwable $previous   Previous throwable used for exception chaining (optional).
     */
    public function __construct(string $message, int $statusCode = 500, ?int $errorCode = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
    }

    /**
     * Get the custom error code associated with the exception.
     *
     * @return null|int The custom error code, or null if not set.
     */
    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    /**
     * Get the HTTP status code associated with the exception.
     *
     * @return int The HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
