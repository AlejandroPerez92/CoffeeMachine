<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Event\OrderLineAdded;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Event\OrderPaid;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\NotEnoughAmountToPayOrder;
use AlexPerez\CoffeeMachine\Shared\Domain\Aggregate\AggregateRoot;
use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final class Order extends AggregateRoot
{
    private OrderId $id;
    private array $lines;
    private Money $total;
    private \DateTimeImmutable $created;
    private bool $paid;
    private bool $hot;

    public function __construct(
        OrderId $id,
        array $lines,
        Money $total,
        \DateTimeImmutable $created,
        bool $paid,
        bool $hot
    ) {
        $this->id = $id;
        $this->lines = $lines;
        $this->total = $total;
        $this->created = $created;
        $this->paid = $paid;
        $this->hot = $hot;
    }

    public static function create(OrderId $id, bool $hot): self
    {
        $order = new self(
            $id,
            [],
            new Money(0),
            new \DateTimeImmutable(),
            false,
            $hot
        );

        // TODO Dispatch domain events
        return $order;
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function lines(): array
    {
        return $this->lines;
    }

    public function total(): Money
    {
        return $this->total;
    }

    public function paid(): bool
    {
        return $this->paid;
    }

    public function created(): \DateTimeImmutable
    {
        return $this->created;
    }

    public static function aggregateId(): string
    {
        return "order.order";
    }

    public function pay(Money $money): void
    {
        if ($money->lessThan($this->total)) {
            throw new NotEnoughAmountToPayOrder($this->total, $money);
        }

        $this->paid = true;
        $this->pushEvent(new OrderPaid($this));
    }

    public function addLine(OrderLine $line): void
    {
        if ($line->units()->lessThanOrEqual(new PositiveInteger(0))) {
            return;
        }

        $this->lines[$line->productName()] = $line;
        $this->total->increment($line->total());

        $this->pushEvent(new OrderLineAdded($this->id, $line));
    }

    public function hot(): bool
    {
        return $this->hot;
    }

}