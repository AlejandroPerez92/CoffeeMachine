<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger;

class PositiveInteger
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new NegativeValueException($value);
        }
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function increment(PositiveInteger $amount): void
    {
        $this->value += $amount->value();
    }

    public function decrement(PositiveInteger $amount): void
    {
        $this->value -= $amount->value();
    }

    public function lessThan(PositiveInteger $amount): bool
    {
        return $this->value < $amount->value();
    }

    public function lessThanOrEqual(PositiveInteger $amount): bool
    {
        return $this->value <= $amount->value();
    }

    public function moreThanOrEqual(PositiveInteger $amount): bool
    {
        return $this->value >= $amount->value();
    }

    public function equal(PositiveInteger $toValue): bool
    {
        return $this->value === $toValue->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}