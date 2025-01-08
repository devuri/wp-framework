<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Handles template rendering using Twig.
 *
 * This mechanism allows conditional checks to determine which template should be loaded.
 * By default, Twigit handles the most common templates, but developers can extend this
 * functionality by adding extra templates to the array.
 *
 * ### Important Notes:
 * - When adding templates, ensure that they are not
 *   unintentionally overriding existing templates. For example:
 *   ```php
 *   ['is_single' => 'my-single-page.twig']
 *   ```
 *   This will replace the default check for `['is_single' => 'single.twig']`.
 * - This feature offers powerful customization capabilities by enabling developers to
 *   define their own conditionals and templates. Custom conditionals, such as:
 *   ```php
 *   ['is_promo' => 'promo.twig']
 *   ```
 *   can be implemented and managed independently, allowing for additional data and
 *   custom `context` to be passed to Twig.
 *
 * @var array $extraTemplates Optional. An associative array of conditional checks
 *            mapped to their corresponding Twig templates.
 *            Default empty array.
 */
$extraTemplates = [];

/*
 * Retrieves the Twigit environment with specified options and templates.
 *
 * @return \Twigit\Twigit The Twigit instance with autoescape disabled and additional templates configured.
 *
 */
return twig(['autoescape' => true], $extraTemplates);
