<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Infrastructure\Ui;

use Deliverea\CoffeeMachine\Order\Application\Query\GetOrderQueryObjectInterface;
use Deliverea\CoffeeMachine\Order\Domain\Order;
use Deliverea\CoffeeMachine\Order\Domain\OrderLine;

final class ConsoleQueryObject implements GetOrderQueryObjectInterface
{
    private string $orderId;
    private bool $isExtraHot;
    private bool $stick;
    private int $sugars;
    private string $product;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }


    public function orderId(): string
    {
        return $this->orderId;
    }

    public function fill(Order $order)
    {
        $this->product = $this->getFirstOrderLine($order)->productName();
        $this->isExtraHot = $order->hot();
        $this->stick = (bool) $this->getUnitsByName($order, 'stick');
        $this->sugars = $this->getUnitsByName($order, 'sugar');
    }

    private function getUnitsByName(Order $order, string $productName): int
    {
        /** @var OrderLine $line */
        foreach ($order->lines() as $line) {
            if ($productName === $line->productName()) {
                return $line->units()->value();
            }
        }

        return 0;
    }

    private function getFirstOrderLine(Order $order): OrderLine
    {
        /** @var OrderLine $line */
        foreach ($order->lines() as $orderLine) {
            return $orderLine;
        }
        throw new \LogicException("The order not have orders lines");
    }

    public function isExtraHot(): bool
    {
        return $this->isExtraHot;
    }

    public function stick(): bool
    {
        return $this->stick;
    }

    public function sugars(): int
    {
        return $this->sugars;
    }

    public function product(): string
    {
        return $this->product;
    }
}