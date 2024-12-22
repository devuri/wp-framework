<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use WPframework\AppFactory;

require_once \dirname(__FILE__, 2) . '/vendor/autoload.php';

\define('TRY_WITH_NO_DB', true);

$start = microtime(true);

$siteAppFactory = AppFactory::create(__DIR__);
// $siteAppFactory->filter(['config','kernel']);
// $siteAppFactory->filter([]);
AppFactory::run();

// End timing
$end = microtime(true);
$executionTime = $end - $start;
// 0.027
dump("execution time:$executionTime: " . toMillisecond($executionTime));

return true;
exit;
