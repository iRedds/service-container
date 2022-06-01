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

    'array_indentation'      => true,
//    'binary_operator_spaces' => [
//        'default'   => 'align_single_space_minimal',
//    ],

    'no_unused_imports'                 => true,
    'no_trailing_whitespace_in_comment' => true,
    'single_line_comment_spacing'       => true,
    'not_operator_with_successor_space' => true,
    'nullable_type_declaration_for_default_null_value' => [
        'use_nullable_type_declaration'     => true,
    ],
    'no_superfluous_phpdoc_tags'        => [
        'allow_mixed' => true,
    ],
])
    ->setCacheFile(__DIR__.'/tmp/.php-cs-fixer.cache')
    ->setFinder($finder)
    ;
