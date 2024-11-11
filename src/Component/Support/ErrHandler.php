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

use Whoops\Handler\Handler;
use Psr\Log\LoggerInterface;


class ErrHandler extends Handler
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle()
    {
        $exception = $this->getException();

		$this->logger->error($exception->getMessage(), [
			'exception' => $exception,
			'trace' => $exception->getTraceAsString()
		]);

		\WPframework\Terminate::exit($exception);
    }
}
