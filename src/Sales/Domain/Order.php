<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Domain;

final class Order
{
    private string $id;
    private array $lines;
    private bool $paid;

    public function __construct(string $id, array $lines, bool $paid)
    {
        $this->id = $id;
        $this->lines = $lines;
        $this->paid = $paid;
    }

    public function addLine(OrderLine $line)
    {
        $this->lines[] = $line;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function lines(): array
    {
        return $this->lines;
    }

    public function paid(): bool
    {
        return $this->paid;
    }

    public function pay(): void
    {
        $this->paid = true;
    }

}