<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;
use Deliverea\CoffeeMachine\Shared\Domain\Money\Money;

final class PayOrderCommandHandler
{
    private OrderRepositoryInterface $orderRepository;
    private EventBusInterface $eventBus;

    public function __construct(OrderRepositoryInterface $orderRepository, EventBusInterface $eventBus)
    {
        $this->orderRepository = $orderRepository;
        $this->eventBus = $eventBus;
    }

    public function handle(PayOrderCommand $command): void
    {
        $order = $this->orderRepository->getByIdOrFail($command->orderId());
        $order->pay(new Money($command->amount()));
        $this->orderRepository->save($order);
        $this->eventBus->publish(...$order->pullDomainEvents());
    }
}