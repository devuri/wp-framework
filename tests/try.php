<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Filesystem\Filesystem;
use WPframework\AppFactory;
use WPframework\EnvType;
use WPframework\Http\Message\ServerRequest;

require_once \dirname(__FILE__, 2) . '/vendor/autoload.php';

\define('TRY_WITH_NO_DB', true);

$start = microtime(true);

$request = new ServerRequest(
    'GET',
    'https://test.example.com/api/resource',
    ['Accept' => 'application/json']
);

$siteAppFactory = AppFactory::create(__DIR__, null, $request);
// $siteAppFactory->filter(['config','kernel']);
// $siteAppFactory->filter([]);
// $fwdb = WPframework\Support\Configs::wpdb();
// dump($fwdb);

AppFactory::run();

// End timing
$end = microtime(true);
$executionTime = $end - $start;
// 0.027
dump("execution time:$executionTime: " . toMillisecond($executionTime));


// $env = new EnvType(new Filesystem());
// dump($env->readOnly(__DIR__. '/.env.local'));

return true;
exit;
