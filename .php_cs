<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('bin')
    ->exclude('vendor')
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@Symfony' => true,
        'ordered_imports' => true,
        'yoda_style' => false,
    ))
    ->setFinder($finder);
