<?php

declare(strict_types=1);

/**
 * Configuration of code style fixer and checker for this library.
 * This configuration compatible with friendsofphp/php-cs-fixer "^3.65.0".
 */

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$config = new Config();
$config->setRules([
    '@PER-CS' => true,
    'cast_spaces' => [
        'space' => 'none',
    ],
    'single_line_empty_body' => false,
]);
$config->setFinder($finder);

return $config;
