<?php

namespace WPframework;

use Exception;
use InvalidArgumentException;
use Throwable;
use WPframework\Exceptions\HttpException;
use WPframework\Interfaces\ExitInterface;
use WPframework\Support\Configs;

class Terminate
{
    protected $exitHandler;
    protected $exception;
    protected $statusCode;
    protected $errorCode;
    protected $request;

    /**
     * @param Throwable $exception
     * @param int       $statusCode
     * @param array     $request
     */
    public function __construct(Throwable $exception, ?int $statusCode = 500, array $request = [], ?ExitInterface $exit = null)
    {
        $this->request    = $request;
        $this->statusCode = $statusCode;
        $this->exception  = new HttpException($exception->getMessage(), $this->statusCode, null, $exception);
        $this->exitHandler = $exit ?? new ExitHandler();
    }

    /**
     * Handles termination of the script execution by sending an HTTP status code, displaying an error page,
     * and logging the exception.
     *
     * @param Throwable $exception The exception to log.
     * @param array     $request
     */
    public static function exit(?Throwable $exception, ?int $statusCode = 500, string $pageTitle = 'Service Unavailable', array $request = []): void
    {
        $terminator = new self($exception, $statusCode, $request);
        $terminator->sendHttpStatusCode();
        $terminator->renderPage($pageTitle);
        $terminator->logException($exception);
        $terminator->exitHandler->terminate(1);
    }

    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;
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
    protected function renderPage(string $pageTitle): void
    {
        if (PHP_SAPI === 'cli') {
            dump($this->exception->getMessage());
            dd($this->exception);

            return;
        }
        $this->pageHeader($pageTitle);
        ?>
            <div id="error-page" class="" style="margin-top: 4em; padding: 1.4em; background: #fff; box-shadow: rgba(17, 17, 26, 0.05) 0px 4px 16px, rgba(17, 17, 26, 0.05) 0px 8px 32px;">
                <h1 style="font-style: oblique;font-weight: 400;margin-bottom: 1em;">
                    Exception
                </h1>
                <div style="margin-bottom: 2em; font-size: large;">
                    <p>
                        <?php echo htmlspecialchars($this->exception->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
                <p>
                    <?php echo $this->linkUrl(); ?>
                    <?php echo $this->homeUrl(); ?>
                    <?php echo $this->loginUrl(); ?>
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

    protected function linkUrl(): string
    {
        $path = htmlspecialchars(($this->request['path'] ?? null), ENT_QUOTES);
        $linkedUrl = "{$path}";

        return '<a class="btn btn-outline" href="' . $linkedUrl . '">Retry</a>';
    }

    protected function homeUrl(): string
    {
        $linkedhomeUrl = env('WP_HOME', '#');

        return '<a class="btn btn-outline" href="' . $linkedhomeUrl . '">Go Home</a>';
    }

    protected function loginUrl(): ?string
    {
        $adminLoginUrl = env('ADMIN_LOGIN_URL', '#');

        if (401 === $this->statusCode) {
            return "<a class=\"btn btn-outline\" href=\"{$adminLoginUrl}\">Login</a>";
        }

        return null;
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
    protected static function showStackTrace(): bool
    {
        $isProd = Configs::isInProdEnvironment();
        if ($isProd) {
            return false;
        }
        if (configs()->get('terminate.debugger') || ! $isProd) {
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

    private function pageHeader(string $pageTitle): void
    {
        ?>
        <!DOCTYPE html><html lang='en'>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" rel="stylesheet">
            <meta charset="utf-8">
			<meta name="robots" content="noindex, nofollow">
            <meta name="viewport" content="width=device-width, initial-scale=1">

			<!-- Fonts -->
	        <link rel="preconnect" href="https://fonts.bunny.net">
	        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600" rel="stylesheet" />

            <title><?php echo $pageTitle; ?></title>
			<?php self::pageStyles(); ?>
        </head>
        <body id="page" style="background: #efefef; font-family: figtree;">
        <?php
    }

    private function pageFooter(): void
    {
        ?>
        <footer align="center" style="margin-top: 0px; padding: 1em; text-align: end; font-size: small;">
            Status Code: <span style="color:#afafaf"><?php echo (string) $this->statusCode; ?></span>
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
		samp {
			color: unset;
			background: none;
			font-size: 1em;
		}
		ul li {
			margin-bottom: 10px;
			font-size: 14px ;
		}
		a {
			color: #0073aa;
		}
        .btn-outline {
            border: solid thin;
            border-radius: 4px;
            padding: 6px 12px;
        }

	</style>
	<?php
    }
}
