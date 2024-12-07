<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' =>        ['syntax' => 'short'],
    ])
    ->setFinder($finder);
