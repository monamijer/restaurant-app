<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
    ])
    ->name('*.php')
    ->exclude([
        'vendor',
        'public/assets',
    ]);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR12' => true,

        // Arrays
        'array_syntax' => [
            'syntax' => 'short',
        ],

        // Imports
        'no_unused_imports' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
        ],

        // Quotes
        'single_quote' => true,

        // Operators
        'binary_operator_spaces' => [
            'default' => 'single_space',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'not_operator_with_successor_space' => false,

        // Trailing commas
        'trailing_comma_in_multiline' => [
            'elements' => [
                'arrays',
                'arguments',
                'parameters',
            ],
        ],

        // Whitespace / blank lines
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => true,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,

        // Methods / functions
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'return_type_declaration' => [
            'space_before' => 'none',
        ],

        // Style
        'yoda_style' => false,
        'increment_style' => [
            'style' => 'post',
        ],

        // PHPDoc
        'phpdoc_align' => false,
    ])
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setFinder($finder);