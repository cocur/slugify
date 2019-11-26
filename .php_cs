<?php

return PhpCsFixer\Config::create()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->files()
            ->in(__DIR__ . '/bin')
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
            ->append([__FILE__])
            ->notName('DefaultRuleProvider.php')
    )
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
    ]);
