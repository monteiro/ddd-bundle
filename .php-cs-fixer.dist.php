<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->ignoreDotFiles(false)
    ->ignoreVCSIgnored(true)
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PHP80Migration:risky' => true,
        '@PHP74Migration' => true,
        '@PHP74Migration:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true
    ])
    ->setFinder($finder)
;
