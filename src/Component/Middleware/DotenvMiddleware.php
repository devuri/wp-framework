<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class DotenvMiddleware extends AbstractMiddleware
{
    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->when();

		$_env_files = _envFilesFilter(_supportedEnvFiles(), APP_DIR_PATH);

		$_dotenv = Dotenv::createImmutable(APP_DIR_PATH, $_env_files, true);

		try {
			$_dotenv->load();
		} catch (InvalidPathException $e) {
			tryRegenerateEnvFile(APP_DIR_PATH, APP_HTTP_HOST, $_env_files);

			$debug = [
				'path'        => APP_DIR_PATH,
				'line'        => __LINE__,
				'exception'   => $e,
				'invalidfile' => "Missing env file: {$e->getMessage()}",
			];

			Terminate::exit([ "Missing env file: {$e->getMessage()}", 500, $debug ]);
		} catch (Exception $e) {
			$debug = [
				'path'      => APP_DIR_PATH,
				'line'      => __LINE__,
				'exception' => $e,
			];
			//Terminate::exit([ $e->getMessage(), 500, $debug ]);
		}// end try

        return $handler->handle($request);
    }
}
