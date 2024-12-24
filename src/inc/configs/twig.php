<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if ( ! \defined('ABSPATH')) {
    exit;
}

$twig = twig();

// Collect data for templates
$context = [
    'site_title' => get_bloginfo('name'),
    'site_description' => get_bloginfo('description'),
    'is_home' => is_home(),
    'is_single' => is_single(),
    'is_archive' => is_archive(),
    'posts' => get_posts(),
    'current_post' => is_single() ? get_post() : null,
];

// Route to the correct Twig template
if (is_single()) {
    echo $twig->render('single.html.twig', $context);
} elseif (is_archive()) {
    echo $twig->render('archive.html.twig', $context);
} else {
    echo $twig->render('index.html.twig', $context);
}
