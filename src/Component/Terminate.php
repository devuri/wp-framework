<?php

namespace WPframework;

use Exception;
use InvalidArgumentException;
use Urisoft\DotAccess;

class Terminate
{
    protected $exitHandler;

    protected $errors;

    public function __construct(array $error_details = [], ?ExitInterface $exit = null)
    {
        $this->exitHandler = $exit ?? new ExitHandler();
        $this->errors      = new DotAccess($this->parse_error($error_details));
    }

    /**
     * Handles termination of the script execution by sending an HTTP status code, displaying an error page,
     * and logging the exception.
     *
     * @param array     $error_details Contains the 'message' and optionally the 'status_code'.
     * @param Exception $exception     The exception to log.
     */
    public static function exit(array $error_details, ?Exception $exception = null): void
    {
        $terminator = new self($error_details);
        $terminator->send_http_status_code($terminator->get_error('code'));
        $terminator->render_error_page($terminator->get_error('message'), $terminator->get_error('code'));
        $terminator->log_exception($exception);
        $terminator->exitHandler->terminate(1);
    }

    // mixed returned
    /**
     * @psalm-param 'code'|'message' $key
     */
    public function get_error(string $key)
    {
        return $this->errors->get($key);
    }

    /**
     * Sends the HTTP status code header after validating it.
     *
     * @param int $status_code The HTTP status code to send.
     *
     * @throws InvalidArgumentException If the status code is not valid.
     */
    protected function send_http_status_code(int $status_code): void
    {
        if ($this->is_valid_http_status_code($status_code)) {
            http_response_code($status_code);
        } else {
            throw new InvalidArgumentException("Invalid HTTP status code: {$status_code}");
        }
    }

    /**
     * Checks if the provided status code is a valid HTTP status code.
     *
     * @param int $status_code The HTTP status code to validate.
     *
     * @return bool True if the status code is valid, false otherwise.
     */
    protected function is_valid_http_status_code(int $status_code): bool
    {
        return $status_code >= 100 && $status_code <= 599;
    }

    /**
     * Parses the error details to extract the message and status code.
     *
     * @param array $error_details The error details provided.
     *
     * @return (array|int|mixed|string)[] The message and status code.
     *
     * @psalm-return array{message: 'An error occurred'|mixed, code: 500|mixed, debug: array<never, never>|mixed,...}
     */
    protected function parse_error(array $error_details): array
    {
        $message     = $error_details[0] ?? 'An error occurred';
        $status_code = $error_details[1] ?? 500;
        $debug       = $error_details[2] ?? [];

        $_details['message'] = $message;
        $_details['code']    = $status_code;
        $_details['debug']   = $debug;

        return $_details;
    }

    /**
     * Handles exceptions by sending them to a monitoring tool.
     *
     * @param Exception $exception The caught exception.
     */
    protected function log_exception(?Exception $exception = null): void
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
     *
     * @param string $message     The message to display.
     * @param int    $status_code The HTTP status code.
     */
    protected function render_error_page(string $message, int $status_code): void
    {
        $this->page_header();
        ?>
            <div id="error-page" class="">
                <h1>Raydium: error</h1>
                <p><?php echo $message; ?></p>
                <p>
                    <a class="button btn" href="/">Retry</a>
                </p>
            </div>
            <div>
                <?php
                if ($this->is_prod()) {
                    dump('Raydium: debug data is hidden in production');
                } elseif (config('terminate.debugger')) {
                    dump($this->errors->get('debug'));
                }
        ?>
            </div>
        <?php

        $this->page_footer($status_code);
    }

    private function page_header(string $page_title = 'Service Unavailable'): void
    {
        ?>
        <!DOCTYPE html><html lang='en'>
        <head>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
            <meta http-equiv="Content-Type" content="text/html; charset='UTF-8'" />
            <meta name="viewport" content="width=device-width">
            <title><?php echo $page_title; ?></title>
            <?php self::page_styles(); ?>
        </head>
		<body id="page">
		<?php
    }

    private function page_footer(int $status_code): void
    {
        ?>
        <footer align="center">
			Status Code:<span style="color:#afafaf"><?php echo (string) $status_code; ?></span>
			</footer>
			</body>
		</html>
		<?php
    }

    private function is_prod(): bool
    {
        if (\defined('WP_ENVIRONMENT_TYPE') && \in_array(\constant('WP_ENVIRONMENT_TYPE'), [ 'secure', 'sec', 'production', 'prod' ], true)) {
            return true;
        }

        return false;
    }

    private static function page_styles(): void
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
