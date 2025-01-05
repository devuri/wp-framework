<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class EnvLoader
{
    /**
     * @var null|EnvLoader
     */
    private static ?EnvLoader $instance = null;

    /**
     * The application directory path.
     *
     * @var string
     */
    private string $appDirPath;

    /**
     * The HTTP host.
     *
     * @var string
     */
    private string $httpHost;

    /**
     * Private constructor to prevent instantiation.
     *
     * @param string $appDirPath
     * @param string $httpHost
     */
    private function __construct(string $appDirPath, string $httpHost)
    {
        $this->appDirPath = $appDirPath;
        $this->httpHost = $httpHost;
    }

    /**
     * Get the single instance of the class.
     *
     * @param string $appDirPath
     * @param string $httpHost
     *
     * @return EnvLoader
     */
    public static function init(string $appDirPath, string $httpHost): self
    {
        if (null === self::$instance) {
            self::$instance = new self($appDirPath, $httpHost);
        }

        return self::$instance;
    }

    /**
     * Load environment variables from specified files.
     *
     * @param array   $envFiles
     * @param EnvType $envType
     */
    public function load(array $envFiles, EnvType $envType): Dotenv
    {
        $dotenv = Dotenv::createImmutable($this->appDirPath, $envFiles);

        try {
            $dotenv->load();
        } catch (InvalidPathException $e) {
            $envType->tryRegenerateFile($this->appDirPath, $this->httpHost, $envFiles);

            Terminate::exit(new InvalidPathException($e->getMessage()));
        }

        return $dotenv;
    }
}
