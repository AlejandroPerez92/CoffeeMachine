<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Persistence\Doctrine\Types;

use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class PositiveIntegerType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value)
    {
        return $value instanceof PositiveInteger ? $value->value() : $value;
    }

    public function convertToPHPValue($value)
    {
        return $value !== null ? new PositiveInteger($value) : null;
    }
}