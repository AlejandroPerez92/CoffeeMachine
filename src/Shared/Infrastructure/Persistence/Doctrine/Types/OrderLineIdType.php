<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Persistence\Doctrine\Types;

use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderLineId;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class OrderLineIdType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value)
    {
        return $value instanceof OrderLineId ? $value->value() : $value;
    }

    public function convertToPHPValue($value)
    {
        if ($value instanceof OrderLineId) {
            return $value;
        }

        return $value !== null ? new OrderLineId($value) : null;
    }
}