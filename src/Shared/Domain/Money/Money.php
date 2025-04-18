<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Domain\Money;

use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final class Money
{
    public function __construct(private int $value)
    {
    }

    public static function CreateFromFloat(float $value): self
    {
        $value = (int) round($value * 100, 0);

        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(Money $value): bool
    {
        return $this->value === $value->value();
    }

    public function lessThan(Money $value): bool
    {
        return $this->value < $value->value();
    }

    public function increment(Money $value): self
    {
        $this->value += $value->value();

        return new self($this->value);
    }

    public function multiply(PositiveInteger $value): void
    {
        $this->value = $this->value * $value->value();
    }

    public function toFloat(): float
    {
        return $this->value / 100;
    }
}