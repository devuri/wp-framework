<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Component;

use PHPUnit\Framework\TestCase;
use WPframework\AppConfig;
use WPframework\ConstantBuilder;

/**
 * @group WPframework
 *
 * @covers \WPframework\AppConfig
 *
 * @internal
 */
class AppConfigTest extends TestCase
{
    /**
     * @var ConstantBuilder
     */
    private $constantBuilder;

    protected function setUp(): void
    {
        $this->constantBuilder = new ConstantBuilder();
    }

    /**
     * Test adding a constant using the addConstant method.
     */
    public function test_add_constant(): void
    {
        $appConfig = new AppConfig($this->constantBuilder);

        $appConfig->addConstant('APP_NAME', 'MyApp');

        $this->assertTrue(\defined('APP_NAME'));
        $this->assertEquals('MyApp', \constant('APP_NAME'));
    }

    /**
     * Test adding a constant using the addConst method (alias).
     */
    public function test_add_const(): void
    {
        $appConfig = new AppConfig($this->constantBuilder);

        $appConfig->addConst('APP_VERSION', '1.0.0');

        $this->assertTrue(\defined('APP_VERSION'));
        $this->assertEquals('1.0.0', \constant('APP_VERSION'));
    }

    /**
     * Test retrieving a constant using the getConstant method.
     */
    public function test_get_constant(): void
    {
        $appConfig = new AppConfig($this->constantBuilder);
        $appConfig->addConst('MY_APP_NAME', 'MyApp');

        $this->assertEquals('MyApp', $appConfig->getConstant('MY_APP_NAME'));
    }

    /**
     * Test checking if a constant is defined.
     */
    public function test_is_constant_defined(): void
    {
        $appConfig = new AppConfig($this->constantBuilder);
        $appConfig->addConst('NEW_APP_NAME', 'MyNew-App');

        $this->assertTrue($appConfig->isConstantDefined('NEW_APP_NAME'));
        $this->assertFalse($appConfig->isConstantDefined('NON_EXISTENT_CONSTANT'));
    }

    /**
     * Test setConstantMap method.
     */
    public function test_set_constant_map(): void
    {
        $appConfig = new AppConfig($this->constantBuilder);

        $appConfig->setConstantMap();
        $this->assertIsArray($this->constantBuilder->getConstantMap());
    }

    /**
     * Test getting all defined constants.
     */
    public function test_get_defined_constants(): void
    {
        $appConfig = new AppConfig($this->constantBuilder);

        $appConfig->addConstant('FINAL_APP_NAME', 'MyApp');
        $appConfig->addConstant('FINAL_VERSION', '1.0.2');

        $definedConstants = $appConfig->getDefinedConstants();

        $this->assertEquals([
            'FINAL_APP_NAME' => 'MyApp',
            'FINAL_VERSION' => '1.0.2',
        ], $definedConstants);
    }
}
