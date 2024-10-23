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
use WPframework\ConstantBuilder;

/**
 * @group WPframework
 *
 * @covers \WPframework\ConstantBuilder
 *
 * @internal
 */
class ConstantBuilderTest extends TestCase
{
    /**
     * @var ConstantBuilder
     */
    private $constantBuilder;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        $this->constantBuilder = new ConstantBuilder();
        // $this->constantBuilder->setErrorNotice();
    }

    public function test_define_constant(): void
    {
        $constName = 'MY_CONSTANT';
        $this->assertFalse(\defined($constName));

        $constValue = 'my_value';
        $this->constantBuilder->define($constName, $constValue);

        $this->assertTrue(\defined($constName));

        $this->assertEquals($constValue, \constant($constName));

        // $this->expectException(ConstantAlreadyDefinedException::class);
        // $this->constantBuilder->define($constName, 'new_value');
    }

    /**
     * Test defining a constant that is already defined.
     */
    public function test_define_already_defined_constant(): void
    {
        $this->constantBuilder->define('APP_NAME', 'MyApp');

        $this->constantBuilder->define('APP_NAME', 'MyOtherApp');
        $this->assertEquals('MyApp', \constant('APP_NAME'));
    }

    /**
     * Test checking if a constant is defined.
     */
    public function test_is_defined(): void
    {
        $this->constantBuilder->define('MYAPP_NAME', 'MyApp');

        $this->assertTrue($this->constantBuilder->isDefined('MYAPP_NAME'));
        $this->assertFalse($this->constantBuilder->isDefined('NON_EXISTENT_CONSTANT'));
    }

    public function test_get_constant(): void
    {
        $constName = 'BOOLEAN_CONSTANT';
        $constValue = true;

        $this->constantBuilder->define($constName, $constValue);

        $result = $this->constantBuilder->getConstant('BOOLEAN_CONSTANT');

        $this->constantBuilder->define('SITEAPP_NAME', 'MyApp');

        $this->assertEquals('MyApp', $this->constantBuilder->getConstant('SITEAPP_NAME'));

        $this->assertEquals($constValue, $result);

        $this->assertNull($this->constantBuilder->getConstant('NON_EXISTENT_CONSTANT'));
    }

    /**
     * Test getting all defined constants.
     */
    public function test_get_all_constants(): void
    {
        $this->constantBuilder->define('SITE_NAME', 'MyApp');
        $this->constantBuilder->define('SITE_VERSION', '1.0.3');

        $allConstants = $this->constantBuilder->getAllConstants();

        $this->assertEquals([
            'SITE_NAME' => 'MyApp',
            'SITE_VERSION' => '1.0.3',
        ], $allConstants);
    }

    /**
     * Test setting and getting the constant map.
     */
    public function test_set_constant_map(): void
    {
        $this->constantBuilder->setMap();

        $constantMap = $this->constantBuilder->getConstantMap();
        $this->assertEquals(['disabled'], $constantMap);

        \define('WP_DEBUG', true);
        \define('WP_ENVIRONMENT_TYPE', 'development');

        $this->constantBuilder->define('TEST_CONSTANT', 'value');
        $this->constantBuilder->setMap();
        $constantMap = $this->constantBuilder->getConstantMap();

        $this->assertEquals(['TEST_CONSTANT' => 'value'], $constantMap);
    }

    /**
     * Test hashing sensitive keys.
     */
    public function test_hash_secret(): void
    {
        $config = [
            'DB_USER' => 'admin',
            'DB_PASSWORD' => 'secret',
            'NON_SENSITIVE_KEY' => 'publicValue',
        ];

        $hashedConfig = $this->constantBuilder->hashSecret($config, ['DB_USER', 'DB_PASSWORD']);

        $this->assertNotEquals('admin', $hashedConfig['DB_USER']);
        $this->assertNotEquals('secret', $hashedConfig['DB_PASSWORD']);
        $this->assertEquals('publicValue', $hashedConfig['NON_SENSITIVE_KEY']);
    }

    public function test_is_constant_defined(): void
    {
        $constName = 'ANOTHER_CONSTANT';

        $this->assertFalse($this->constantBuilder->isDefined($constName));

        $this->constantBuilder->define($constName, 'some_value');

        $this->assertTrue($this->constantBuilder->isDefined($constName));
    }

    public function test_get_constant_map(): void
    {
        unset($_ENV['WP_DEBUG']);
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_DEBUG'] = false;
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_DEBUG'] = true;
        $_ENV['WP_ENVIRONMENT_TYPE'] = 'development';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'staging';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'production';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'custom_environment';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'debug';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());
    }

    // public function test_define_constant(): void
    // {
    //     $constName = 'TEST_CONSTANT';
    //     $constValue = 'TestValue';
    //
    //     $this->assertFalse($this->is_defined($constName));
    //
    //     $this->constantBuilder->define($constName, $constValue);
    //
    //     $this->assertTrue($this->is_defined($constName));
    //
    //     $this->assertEquals($constValue, $this->get_constant($constName));
    // }

    // public function test_define_duplicate_constant(): void
    // {
    //     $constName = 'DUPLICATE_CONSTANT';
    //     $constValue1 = 'Value1';
    //     $constValue2 = 'Value2';
    //
    //     $this->constantBuilder->define($constName, $constValue1);
    //
    //     $this->expectException(ConstantAlreadyDefinedException::class);
    //     $this->expectExceptionMessage("Constant: $constName has already been defined");
    //
    //     $this->constantBuilder->define($constName, $constValue2);
    // }
}
