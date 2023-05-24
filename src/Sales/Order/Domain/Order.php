<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Domain;

use AlexPerez\CoffeeMachine\Sales\Order\Domain\Event\OrderLinePaid;
use AlexPerez\CoffeeMachine\Shared\Domain\Aggregate\AggregateRoot;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final class Order extends AggregateRoot
{
    /**
     * @param OrderId $id
     * @param OrderStatus $status
     * @param OrderLine[] $lines
     */
    public function __construct(
        public readonly OrderId $id,
        private array $lines,
        private OrderStatus $status,
    ) {
    }

    public static function create(
        OrderId $id,
        array $lines,
    ): self {
        return new self(
            $id,
            $lines,
            OrderStatus::pending,
        );
    }

    public function addLine(OrderLine $line): void
    {
        $this->lines[] = $line;
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function lines(): array
    {
        return $this->lines;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    public function pay(): void
    {
        $this->status = OrderStatus::paid;

        foreach ($this->lines as $line) {
            $this->pushEvent(new OrderLinePaid(
                $this->id->value(),
                $line->productName,
                $line->total,
            ));
        }
    }

    public static function aggregateId(): string
    {
        return 'sales.order';
    }
}