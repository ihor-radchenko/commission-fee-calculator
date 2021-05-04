<?php

include __DIR__ . '/vendor/autoload.php';

$finder = PhpCsFixer\Finder::create()
    ->in(['src']);

$config = new PhpCsFixer\Config();

return $config->setRules([
        '@Symfony'              => true,
        'no_alternative_syntax' => true,
        'strict_comparison'     => true,
        'strict_param'          => true,
        'yoda_style'            => false,
    ])
    ->setFinder($finder)
    ->setUsingCache(false)
    ->setRiskyAllowed(true);

