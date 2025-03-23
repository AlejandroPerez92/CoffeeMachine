<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->rules([
        \Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector::class,
    ]);
};