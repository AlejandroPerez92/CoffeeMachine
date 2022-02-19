<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Domain;

use Deliverea\CoffeeMachine\Order\Domain\Exception\NotEnoughAmountToPayOrder;
use Deliverea\CoffeeMachine\Shared\Domain\Aggregate\AggregateRoot;
use Deliverea\CoffeeMachine\Shared\Domain\Money\Money;

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
        bool $hot)
    {
        $this->id = $id;
        $this->lines = $lines;
        $this->total = $total;
        $this->created = $created;
        $this->paid = $paid;
        $this->hot = $hot;
    }

    public static function Create(OrderId $id, bool $hot): self
    {
        $order = new self($id, [], new Money(0), new \DateTimeImmutable(), false, $hot);

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
        return "order";
    }

    public function pay(Money $money)
    {
        if ($money->lessThan($this->total)) {
            throw new NotEnoughAmountToPayOrder($this->total, $money);
        }

        $this->paid = true;
    }

    public function addLine(OrderLine $line)
    {
        $this->lines[$line->productName()] = $line;
        $this->total->increment($line->total());
    }

    public function hot(): bool
    {
        return $this->hot;
    }

}