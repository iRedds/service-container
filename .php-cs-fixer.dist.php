<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR12' => true,
    ])
    ->setCacheFile(__DIR__.'/tmp/.php-cs-fixer.cache')
    ->setFinder($finder)
    ;
