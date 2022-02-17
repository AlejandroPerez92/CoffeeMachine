<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Shared\Domain\Money;

final class Money
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
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

    public function toFloat(): float
    {
        return $this->value * 100;
    }
}