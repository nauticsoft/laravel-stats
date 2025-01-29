<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withSkip([
        ClosureToArrowFunctionRector::class,
    ])
    ->withPhpSets(php84: true)
    ->withPreparedSets(deadCode: true);
