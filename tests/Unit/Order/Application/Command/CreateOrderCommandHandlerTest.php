<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Tests\Unit\Order\Application\Command;

use AlexPerez\CoffeeMachine\Order\Order\Application\Command\CreateOrderCommand;
use AlexPerez\CoffeeMachine\Order\Order\Application\Command\CreateOrderCommandHandler;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Order\Order\Infrastructure\InMemoryOrderRepository;
use PHPUnit\Framework\TestCase;

final class CreateOrderCommandHandlerTest extends TestCase
{
    private OrderRepositoryInterface $orderRepository;
    private CreateOrderCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = new InMemoryOrderRepository();
        $this->handler = new CreateOrderCommandHandler($this->orderRepository);
    }

    /**
     * @test
     */
    public function given_order_id_when_handle_then_order_should_be_created()
    {
        $orderId = OrderId::create();
        $this->handler->handle(new CreateOrderCommand($orderId, false));
        $order = $this->orderRepository->getByIdOrFail($orderId);
        self::assertNotNull($order);
    }

    /**
     * @test
     */
    public function given_order_id_and_hot_when_handle_then_order_hot_should_be_created()
    {
        $orderId = OrderId::create();
        $this->handler->handle(new CreateOrderCommand($orderId, true));
        $order = $this->orderRepository->getByIdOrFail($orderId);
        self::assertTrue($order->hot());
    }
}