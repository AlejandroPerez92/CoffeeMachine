<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Persistence\Doctrine\Types;

use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderLineId;
use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;
use MongoDB\BSON\UTCDateTime;

class OrderLineType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        $result = [];
        foreach ($value as $productName => $line) {
            if (!$line instanceof OrderLine) {
                continue;
            }

            $result[$productName] = [
                'id' => $line->id->value(),
                'productName' => $line->productName,
                'total' => $line->total->value(),
                'units' => $line->units->value(),
                'created' => $line->created
            ];
        }

        return $result;
    }

    public function convertToPHPValue($value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        $result = [];
        foreach ($value as $productName => $lineData) {
            $result[$productName] = new OrderLine(
                new OrderLineId($lineData['id']),
                $lineData['productName'],
                new Money($lineData['total']),
                new PositiveInteger($lineData['units']),
                $lineData['created'] instanceof UTCDateTime 
                    ? $lineData['created']->toDateTime() 
                    : new \DateTimeImmutable()
            );
        }

        return $result;
    }
}