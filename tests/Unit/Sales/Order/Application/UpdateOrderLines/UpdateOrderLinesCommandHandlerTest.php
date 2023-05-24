<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Tests\Unit\Sales\Order\Application\UpdateOrderLines;

use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderLines\UpdateOrderLinesCommand;
use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderLines\UpdateOrderLinesCommandHandler;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderStatus;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use PHPUnit\Framework\TestCase;

final class UpdateOrderLinesCommandHandlerTest extends TestCase
{
    private OrderRepositoryInterface $orderRepository;
    private UpdateOrderLinesCommandHandler $commandHandler;

    protected function setUp(): void
    {
        $this->commandHandler = new UpdateOrderLinesCommandHandler(
            $this->orderRepository = $this->createMock(OrderRepositoryInterface::class)
        );
    }

    /**
     * @test
     */
    public function given_not_exists_order_when_handle_then_new_order_should_be_created()
    {
        $order = $this->createOrder(lines: [new OrderLine('tea', 50)]);

        $this->orderRepository
            ->expects($this->once())
            ->method('getOrderOrFail')
            ->willThrowException(new NotFoundOrderException($order->id));

        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $this->commandHandler->handle(new UpdateOrderLinesCommand(
            $order->id->value(),
            'tea',
            50,
        ));
    }

    /**
     * @test
     */
    public function given_the_order_when_handle_then_order_should_have_a_line()
    {
        $order = $this->createOrder();
        $expectedOrder = $this->createOrder(
            orderId: $order->id,
            lines: [new OrderLine('tea', 50)]
        );

        $this->orderRepository
            ->expects($this->once())
            ->method('getOrderOrFail')
            ->willReturn($order);

        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->with($expectedOrder);

        $this->commandHandler->handle(new UpdateOrderLinesCommand($order->id->value(), 'tea', 50));
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