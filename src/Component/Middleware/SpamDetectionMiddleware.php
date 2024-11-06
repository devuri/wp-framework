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

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SpamDetectionMiddleware extends AbstractMiddleware
{
    private $suspiciousKeywords = [
        'вход', 'кракен', 'Kraken',
    ];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $postData = $request->getParsedBody();

        if (\is_array($postData)) {
            $content = self::toString($postData);

            if ($this->containsCyrillicCharacters($content)) {
                return $this->blockRequest('Spam detected: Cyrillic characters found');
            }

            if ($this->containsMixedLanguagePhrases($content)) {
                return $this->blockRequest('Spam detected: Mixed-language phrase detected');
            }

            if ($this->containsSuspiciousKeywords($content)) {
                return $this->blockRequest('Spam detected: Suspicious keyword found');
            }
        }

        return $handler->handle($request);
    }

    /**
     * @param array|string $array
     *
     * @return bool
     */
    protected static function toString($array)
    {
        $result = '';

        if (\is_string($array)) {
            $array = [$array];
        }

        foreach ($array as $element) {
            if (\is_array($element)) {
                $result .= self::toString($element);
            } else {
                $result .= $element . ' ';
            }
        }

        return htmlspecialchars(trim($result), ENT_QUOTES, 'UTF-8');
    }

    private function containsCyrillicCharacters(string $content): bool
    {
        return 1 === preg_match('/[А-Яа-яЁё]+/u', $content);
    }

    private function containsMixedLanguagePhrases(string $content): bool
    {
        return 1 === preg_match('/[А-Яа-яЁё]+.*?\b(?:security|anonymous|resources|guarantee|power|allows|simple|internet)\b/i', $content);
    }

    private function containsSuspiciousKeywords(string $content): bool
    {
        foreach ($this->suspiciousKeywords as $keyword) {
            if (false !== stripos($content, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function blockRequest(string $message): ResponseInterface
    {
        throw new Exception($message);
    }
}
