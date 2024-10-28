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

use Psr\Http\Message\ServerRequestInterface;
use WPframework\EnvType;
use WPframework\Interfaces\EnvSwitcherInterface as Switcher;

class SiteManager
{
    private $configManager;
    private $switcher;
    private $environment;
    private $errorHandler;
    private $errorLogsDir;

    public function __construct(ConstantBuilder $configManager)
    {
        $this->configManager = $configManager;
        $this->errorLogsDir  = self::setErrorLogsDir(APP_DIR_PATH);
        $this->errorHandler  = false;
    }

    /**
     * @return static
     */
    public function constants(): self
    {
        $this->setDatabase();
        $this->setSiteUrl();
        $this->setAssetUrl();
        $this->setMemory();
        $this->setOptimize();
        $this->setForceSsl();
        $this->setAutosave();
        $this->setSalts();

        return $this;
    }

    /**
     * Set the environment.
     *
     * @param null|string $environment
     *
     * @return static
     */
    public function setEnvironment(?string $environment): self
    {
        $this->environment = $this->determineEnvironment($environment);
        $this->configManager->addConstant('WP_DEVELOPMENT_MODE', self::wpDevelopmentMode());

        if ($this->isEnvironmentNull()) {
            $this->configManager->addConstant('WP_ENVIRONMENT_TYPE', env('WP_ENVIRONMENT_TYPE') ?? self::getConstant('environment'));
        } else {
            $this->configManager->addConstant('WP_ENVIRONMENT_TYPE', $this->environment);
        }

        return $this;
    }

    /**
     * Debug setup.
     *
     * @param null|string $errorLogsDir
     *
     * @return static
     */
    public function debug(?string $errorLogsDir): self
    {
        if ($this->isEnvironmentNull() && env('WP_ENVIRONMENT_TYPE')) {
            $this->resetEnvironment(env('WP_ENVIRONMENT_TYPE'));
        }

        if ( ! EnvType::isValid($this->environment)) {
            $this->switcher->createEnvironment('production', $this->errorLogsDir);
        } else {
            $this->switcher->createEnvironment($this->environment, $this->errorLogsDir);
        }

        return $this;
    }

    /**
     * @return static
     */
    public function appSetup(ServerRequestInterface $request): self
    {
        $this->setEnvironment(env('WP_ENVIRONMENT_TYPE'));
        $this->debug($this->errorLogsDir);
        $this->enableErrorHandler($request);

        return $this;
    }

    public function setSwitcher(Switcher $switcher): void
    {
        $this->switcher = $switcher;
    }

    public function setSiteUrl(): void
    {
        $this->configManager->addConstant('WP_HOME', env('WP_HOME'));
        $this->configManager->addConstant('WP_SITEURL', env('WP_SITEURL'));
    }

    public function setAssetUrl(): void
    {
        $this->configManager->addConstant('ASSET_URL', env('ASSET_URL'));
    }

    public function setOptimize(): void
    {
        $this->configManager->addConstant('CONCATENATE_SCRIPTS', env('CONCATENATE_SCRIPTS') ?? self::getConstant('optimize'));
    }

    public function setMemory(): void
    {
        $this->configManager->addConstant('WP_MEMORY_LIMIT', env('MEMORY_LIMIT') ?? self::getConstant('memory'));
        $this->configManager->addConstant('WP_MAX_MEMORY_LIMIT', env('MAX_MEMORY_LIMIT') ?? self::getConstant('memory'));
    }

    public function setForceSsl(): void
    {
        $this->configManager->addConstant('FORCE_SSL_ADMIN', env('FORCE_SSL_ADMIN') ?? self::getConstant('ssl_admin'));
        $this->configManager->addConstant('FORCE_SSL_LOGIN', env('FORCE_SSL_LOGIN') ?? self::getConstant('ssl_login'));
    }

    public function setAutosave(): void
    {
        $this->configManager->addConstant('AUTOSAVE_INTERVAL', env('AUTOSAVE_INTERVAL') ?? self::getConstant('autosave'));
        $this->configManager->addConstant('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?? self::getConstant('revisions'));
    }

    public function setDatabase(): void
    {
        $this->configManager->addConstant('DB_NAME', env('DB_NAME'));
        $this->configManager->addConstant('DB_USER', env('DB_USER'));
        $this->configManager->addConstant('DB_PASSWORD', env('DB_PASSWORD'));
        $this->configManager->addConstant('DB_HOST', env('DB_HOST') ?? self::getConstant('db_host'));
        $this->configManager->addConstant('DB_CHARSET', env('DB_CHARSET') ?? 'utf8mb4');
        $this->configManager->addConstant('DB_COLLATE', env('DB_COLLATE') ?? '');
    }

    public function setSalts(): void
    {
        $this->configManager->addConstant('AUTH_KEY', env('AUTH_KEY'));
        $this->configManager->addConstant('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
        $this->configManager->addConstant('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
        $this->configManager->addConstant('NONCE_KEY', env('NONCE_KEY'));
        $this->configManager->addConstant('AUTH_SALT', env('AUTH_SALT'));
        $this->configManager->addConstant('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
        $this->configManager->addConstant('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
        $this->configManager->addConstant('NONCE_SALT', env('NONCE_SALT'));
        $this->configManager->addConstant('DEVELOPER_ADMIN', env('DEVELOPER_ADMIN') ?? '0');
    }

    /**
     * Determine the environment based on the provided string.
     *
     * @param null|string $environment
     *
     * @return string
     */
    public function determineEnvironment(?string $environment): string
    {
        return trim($environment);
    }

    /**
     * @return true
     */
    public function enableErrorHandler(ServerRequestInterface $request): bool
    {
        if ($this->errorHandler) {
            return true;
        }

        // Enable error handling logic here
        // Example: set_error_handler([$this, 'handleError']);

        $this->errorHandler = true;

        return true;
    }

    /**
     * @return null|int|string|true
     *
     * @psalm-return '256M'|'localhost'|'production'|10|180|null|true
     */
    public static function getConstant(string $key)
    {
        $constants = [
            'environment' => 'production',
            'debug'       => true,
            'db_host'     => 'localhost',
            'optimize'    => true,
            'memory'      => '256M',
            'ssl_admin'   => true,
            'ssl_login'   => true,
            'autosave'    => 180,
            'revisions'   => 10,
        ];

        return $constants[$key] ?? null;
    }

    private function isEnvironmentNull(): bool
    {
        return empty($this->environment);
    }

    private static function wpDevelopmentMode(): string
    {
        return env('WP_DEVELOPMENT_MODE') ?? '';
    }

    private function resetEnvironment(?string $reset): void
    {
        $this->environment = $reset;
    }

    private static function setErrorLogsDir(string $appPath, ?string $tenantId = null): string
    {
        $logFileName = mb_strtolower(gmdate('m-d-Y')) . '.log';
        $errorLogsDirSuffix = $tenantId ? "/{$tenantId}/" : '/';

        return $appPath . '/storage/logs/wp-errors' . $errorLogsDirSuffix . "debug-{$logFileName}";
    }
}
