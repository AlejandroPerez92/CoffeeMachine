<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Shared\Domain\Money\Money;

final class PayOrderCommandHandler
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handle(PayOrderCommand $command): void
    {
        $order = $this->orderRepository->getByIdOrFail($command->orderId());
        $order->pay(new Money($command->amount()));
        $this->orderRepository->save($order);
    }
}