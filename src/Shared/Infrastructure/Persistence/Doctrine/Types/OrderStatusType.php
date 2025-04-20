<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Persistence\Doctrine\Types;

use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderStatus;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class OrderStatusType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value): mixed
    {
        return $value instanceof OrderStatus ? $value->value : $value;
    }

    public function convertToPHPValue($value): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof OrderStatus) {
            return $value;
        }

        return OrderStatus::from($value);
    }
}