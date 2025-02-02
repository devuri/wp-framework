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

namespace WPframework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * GitHubWebhookValidationMiddleware.
 *
 * This middleware validates that an incoming request has a valid GitHub HMAC signature.
 * If valid, sets isValidGitHubSignature = true, otherwise false.
 */
class GitHubWebhookMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private string $gitHubSecret;

    /**
     * Process the incoming server request and return a response.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->gitHubSecret = env('GITHUB_SECRET');

        // The standard GitHub signature header for HMAC-SHA256 is "X-Hub-Signature-256"
        $signatureHeader = $request->getHeaderLine('X-Hub-Signature-256');

        $payload = (string) $request->getBody();
        $isValid = false;

        if (!empty($signatureHeader)) {
            // GitHub prepends "sha256=" to the actual HMAC
            $calculatedSignature = 'sha256=' . hash_hmac('sha256', $payload, $this->gitHubSecret);

            if (hash_equals($calculatedSignature, $signatureHeader)) {
                $isValid = true;
            }
        }

        $request = $request->withAttribute('isValidGitHubSignature', $isValid);

        return $handler->handle($request);
    }
}
