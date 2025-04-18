<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Persistence\Doctrine\Types;

use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class MoneyType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value)
    {
        return $value instanceof Money ? $value->value() : $value;
    }

    public function convertToPHPValue($value)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Money) {
            return $value;
        }

        if (is_array($value)) {
            $value = $value['amount'] ?? $value[0] ?? 0;
        }

        return new Money((int) $value);
    }
}
