<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__ . '/Test/TestAsset')
;

return Symfony\CS\Config\Config::create()
    ->fixers(array('-Psr0Fixer'))
    ->finder($finder)
;
