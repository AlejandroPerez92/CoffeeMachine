<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Persistence\Doctrine\Types;

use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class OrderIdType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value)
    {
        return $value instanceof OrderId ? $value->value() : $value;
    }

    public function convertToPHPValue($value)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof OrderId) {
            return $value;
        }

        return new OrderId((string)$value);
    }
}
