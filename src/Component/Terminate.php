<?php

namespace WPframework;

use Exception;
use InvalidArgumentException;
use Throwable;
use WPframework\Exceptions\HttpException;
use WPframework\Interfaces\ExitInterface;

class Terminate
{
    protected $exitHandler;
    protected $exception;
    protected $statusCode;

    /**
     * @param Throwable $exception
     * @param int       $statusCode
     */
    public function __construct(Throwable $exception, ?int $statusCode = 500, ?ExitInterface $exit = null)
    {
        $this->exitHandler = $exit ?? new ExitHandler();
        $this->statusCode  = $statusCode;
        $this->exception   = new HttpException($exception->getMessage(), $this->statusCode, 0, $exception);
    }

    /**
     * Handles termination of the script execution by sending an HTTP status code, displaying an error page,
     * and logging the exception.
     *
     * @param Throwable $exception The exception to log.
     */
    public static function exit(?Throwable $exception, ?int $statusCode = 500): void
    {
        $terminator = new self($exception, $statusCode);
        $terminator->sendHttpStatusCode();
        $terminator->renderPage();
        $terminator->logException($exception);
        $terminator->exitHandler->terminate(1);
    }

    /**
     * Sends the HTTP status code header after validating it.
     *
     * @throws InvalidArgumentException If the status code is not valid.
     */
    protected function sendHttpStatusCode(): void
    {
        if (self::isValidHttpStatusCode($this->statusCode)) {
            http_response_code($this->statusCode);
        } else {
            throw new InvalidArgumentException("Invalid HTTP status code: {$this->statusCode}");
        }
    }

    /**
     * Checks if the provided status code is a valid HTTP status code.
     *
     * @param int $statusCode The HTTP status code to validate.
     *
     * @return bool True if the status code is valid, false otherwise.
     */
    protected static function isValidHttpStatusCode(int $statusCode): bool
    {
        return $statusCode >= 100 && $statusCode <= 599;
    }

    /**
     * Handles exceptions by sending them to a monitoring tool.
     *
     * @param Throwable $exception The caught exception.
     */
    protected function logException(?Throwable $exception = null): void
    {
        if (\is_null($exception)) {
            return;
        }
        // TODO Assuming Sentry is set up and configured.
        // Sentry\captureException($exception);

        // TODO Optionally, log the exception or perform additional actions
        // error_log($exception->getMessage());
    }

    /**
     * Renders the error page with a given message and status code.
     */
    protected function renderPage(): void
    {
        $this->pageHeader();
        ?>
            <div id="error-page" class="">
                <h1>Exception</h1>
                <p><?php echo htmlspecialchars($this->exception->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
                <p>
                    <a class="button btn" href="/">Retry</a>
                </p>
            </div>
            <div>
                <?php if (self::showStackTrace()) {
                    $this->outputDebugInfo();
                } ?>
            </div>
        <?php

        $this->pageFooter();
    }

    /**
     * Determines whether to display a stack trace.
     *
     * This method checks various conditions to decide if a stack trace should be
     * shown. If the application is in a production environment, the stack trace
     * will not be displayed. Otherwise, it considers the `terminate.debugger`
     * configuration value or whether the application is not in a production
     * environment to make the determination.
     *
     * @return bool True if the stack trace should be displayed, false otherwise.
     */
    protected static function showStackTrace()
    {
        if (self::isInProdEnvironment()) {
            return false;
        }
        if (config('terminate.debugger') || ! self::isInProdEnvironment()) {
            return true;
        }

        return false;
    }

    /**
     * Determines if the application is running in a production environment.
     *
     * This method checks the current environment against a list of production
     * environment identifiers. The list of identifiers can be configured via the
     * `prod` configuration key or will default to common production identifiers
     * such as 'secure', 'sec', 'production', and 'prod'.
     *
     * @return bool True if the application is in a production environment, false otherwise.
     */
    protected static function isInProdEnvironment(): bool
    {
        if (config('prod') && \is_array(config('prod'))) {
            $prodEnvironments = config('prod');
        } else {
            $prodEnvironments = ['secure', 'sec', 'production', 'prod'];
        }

        if (\in_array(env('WP_ENVIRONMENT_TYPE'), $prodEnvironments, true)) {
            return true;
        }

        return false;
    }

    /**
     * Outputs detailed debug information if in a non-production environment.
     */
    protected function outputDebugInfo(): void
    {
        if ($this->exception) {
            dump([
                'Exception Message' => $this->exception->getMessage(),
                'File' => $this->exception->getFile(),
                'Line' => $this->exception->getLine(),
                'Trace' => $this->exception->getTraceAsString(),
            ]);

            if ($this->exception->getPrevious()) {
                $previous = $this->exception->getPrevious();
                dump([
                    'Previous Exception Message' => $previous->getMessage(),
                    'File' => $previous->getFile(),
                    'Line' => $previous->getLine(),
                    'Trace' => $previous->getTraceAsString(),
                ]);
            }
        }
    }

    private function pageHeader(string $pageTitle = 'Service Unavailable'): void
    {
        ?>
        <!DOCTYPE html><html lang='en'>
        <head>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
            <meta http-equiv="Content-Type" content="text/html; charset='UTF-8'" />
            <meta name="viewport" content="width=device-width">
            <title><?php echo $pageTitle; ?></title>
            <?php self::pageStyles(); ?>
        </head>
        <body id="page">
        <?php
    }

    private function pageFooter(): void
    {
        ?>
        <footer align="center">
            Status Code:<span style="color:#afafaf"><?php echo (string) $this->statusCode; ?></span>
            </footer>
            </body>
        </html>
        <?php
    }

    private static function pageStyles(): void
    {
        ?>
        <style type="text/css">
            html {
                background: #f1f1f1;
            }
            body {
                color: #444;
                margin: 2em auto;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                padding: 0;
            }
            h1 {
                clear: both;
                color: #0073aa;
                font-size: 24px;
                margin: 0 0 0 0;
                padding: 0;
                padding-bottom: 7px;
                font-weight: inherit;
            }
            footer {
                clear: both;
                color: #cdcdcd;
                margin-top: 0px !important;
                margin: 30px 0 0 0;
                padding-bottom: 24px !important;
                padding: 24px;
                padding-bottom: 7px;
                font-size: small;
                text-transform: uppercase;
            }
            samp {
                color: unset;
                background: none;
                font-size: 1em;
            }
            #error-page {
                background: #fff;
                margin-top: 50px;
                -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
                box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
                padding: 1.6em 2em;
            }
            #error-page p,
            #error-page .die-message {
                line-height: 1.5;
                margin: 25px 0 20px;
            }
            #error-page code {
                font-family: Consolas, Monaco, monospace;
            }
            ul li {
                margin-bottom: 10px;
                font-size: 14px ;
            }
            a {
                color: #0073aa;
            }
        </style>
        <?php
    }
}
