<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Support\Services;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use WPframework\Support\Services\IDGenerator;

/**
 * Tests for IDGenerator.
 *
 * @group WPframework\Support\Services
 *
 * @covers \WPframework\Support\IDGenerator
 *
 * @internal
 */
class IDGeneratorTest extends TestCase
{
    public function test_random_id_generation(): void
    {
        $config = $this->loadConfig('random_config.json');
        $generator = new IDGenerator($config);

        $tenantId = $generator->generateID()->getTenantId();
        $id = $generator->getID();
        $randomId = $generator->getTenantId($id);

        $this->assertMatchesRegularExpression('/^RND-[A-Z0-9]{6}-TEST$/', $tenantId);
        $this->assertGreaterThanOrEqual($config['random_length'], \strlen($randomId['id']));
        $this->assertLessThanOrEqual($config['constraints']['max_length'], \strlen($randomId['id']));
    }

    public function test_sequential_id_generation(): void
    {
        $config = $this->loadConfig('sequential_config.json');
        $generator = new IDGenerator($config);

        $id1 = $generator->generateID();
		$definedID1 = $id1->getTenantId($id1->getID());
		$id1->getID();

        $id2 = $generator->generateID();
		$definedID2 = $id2->getTenantId($id2->getID());
        $id2->getID();

        $this->assertEquals("SEQ_0000100000_USR", $definedID1);
        $this->assertEquals("SEQ_0000100100_USR", $definedID2);
        $this->assertGreaterThanOrEqual($config['constraints']['min_length'], \strlen($id1->getID()));
        $this->assertLessThanOrEqual($config['constraints']['max_length'], \strlen($id2->getID()));
    }

    public function test_hash_id_generation(): void
    {
        $config = $this->loadConfig('hash_config.json');
        $generator = new IDGenerator($config);

        $id = $generator->generateID()->getTenantId();

        $this->assertMatchesRegularExpression('/^HASH[A-Fa-f0-9]{16}$/', $id);
        $this->assertEquals($config['constraints']['min_length'], \strlen($id));
    }

    public function test_uuid_generation(): void
    {
        $config = $this->loadConfig('uuid_config.json');
        $generator = new IDGenerator($config);

        $id = $generator->generateID()->getTenantId();

        $this->assertMatchesRegularExpression('/^UUID:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $id);
        $this->assertEquals($config['constraints']['min_length'], \strlen($id));
    }

    public function test_random_id_uniqueness(): void
    {
        $config = $this->loadConfig('random_config.json');
        $generator = new IDGenerator($config);

        $ids = [];
        for ($i = 0; $i < 100; $i++) {
            $id = $generator->generateID()->getTenantId();
            $this->assertNotContains($id, $ids);
            $ids[] = $id;
        }
    }

    public function test_collision_policy_append_random_suffix(): void
    {
        $config = $this->loadConfig('random_config.json');
        $existingIDs = ["RND-123456-TEST"];
        $generator = new IDGenerator($config, $existingIDs);

        $id = $generator->generateID()->getTenantId();

        $this->assertNotEquals("RND-123456-TEST", $id);
        $this->assertStringStartsWith("RND-", $id);
        $this->assertStringEndsWith("-TEST", $id);
    }
    private function loadConfig($filename)
    {
        $filePath = APP_TEST_PATH . "/fixtures/tenentconfigs/{$filename}";
        if ( ! file_exists($filePath)) {
            throw new RuntimeException("Configuration file {$filename} not found.");
        }

        return json_decode(file_get_contents($filePath), true);
    }
}
