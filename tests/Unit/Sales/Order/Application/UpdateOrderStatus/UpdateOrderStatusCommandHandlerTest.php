<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Tests\Unit\Sales\Order\Application\UpdateOrderStatus;

use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderStatus\UpdateOrderStatusCommand;
use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderStatus\UpdateOrderStatusCommandHandler;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderStatus;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use PHPUnit\Framework\TestCase;

final class UpdateOrderStatusCommandHandlerTest extends TestCase
{
    private OrderRepositoryInterface $orderRepository;
    private UpdateOrderStatusCommandHandler $commandHandler;

    protected function setUp(): void
    {
        $this->commandHandler = new UpdateOrderStatusCommandHandler(
            $this->orderRepository = $this->createMock(OrderRepositoryInterface::class),
            $this->createMock(EventBusInterface::class),
        );
    }

    /**
     * @test
     */
    public function given_not_exists_order_when_handle_then_exception_should_be_throws()
    {
        $order = $this->createOrder(lines: [new OrderLine('tea', 50)]);
        $this->expectException(NotFoundOrderException::class);
        $this->orderRepository
            ->expects($this->once())
            ->method('getOrderOrFail')
            ->willThrowException(new NotFoundOrderException($order->id));

        $this->commandHandler->handle(new UpdateOrderStatusCommand(
            $order->id->value(),
            'paid',
        ));
    }

    /**
     * @test
     */
    public function given_pending_order_when_handle_then_order_should_update_status()
    {
        $order = $this->createOrder(status: OrderStatus::pending);
        $expectedOrder = $this->createOrder(
            orderId: $order->id,
            status: OrderStatus::paid,
        );

        $this->orderRepository
            ->expects($this->once())
            ->method('getOrderOrFail')
            ->willReturn($order);

        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->with($expectedOrder);

        $this->commandHandler->handle(new UpdateOrderStatusCommand($order->id->value(), 'paid'));
    }

    /**
     * @test
     */
    public function given_paid_order_when_handle_then_nothing_should_be_do()
    {
        $order = $this->createOrder(
            status: OrderStatus::paid,
        );

        $this->orderRepository
            ->expects($this->once())
            ->method('getOrderOrFail')
            ->willReturn($order);

        $this->orderRepository
            ->expects($this->never())
            ->method('save');

        $this->commandHandler->handle(new UpdateOrderStatusCommand($order->id->value(), 'paid'));
    }

    private function createOrder(?OrderId $orderId = null, ?array $lines = null, ?OrderStatus $status = null): Order
    {
        return new Order(
            $orderId ?? OrderId::create(),
            $lines ?? [],
            $status ?? OrderStatus::pending,
        );
    }
}