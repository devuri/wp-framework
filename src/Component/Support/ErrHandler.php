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

use Exception;
use Psr\Log\LoggerInterface;
use Throwable;
use Whoops\Handler\Handler;
use WPframework\Terminate;

class ErrHandler extends Handler
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handleGetException()
    {
        return $this->getException();
    }

    public function handle(): void
    {
        $exception = $this->getException();
        $fullMessage = $this->getExceptionOutput($exception);

        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString(),
        ]);

        if ( ! self::isProd(env('WP_ENVIRONMENT_TYPE'))) {
            Terminate::exit(new Exception($fullMessage));
        }

        Terminate::exit($exception);
    }

    /**
     * @param string $environment
     *
     * @return bool
     */
    protected static function isProd(string $environment): bool
    {
        if (\in_array($environment, [ 'secure', 'sec', 'production', 'prod' ], true)) {
            return true;
        }

        return false;
    }

    /**
     * @param Throwable $exception
     */
    private function getExceptionOutput(Throwable $exception)
    {
        return \sprintf(
            "%s: %s in file %s on line %d",
            \get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }
}
