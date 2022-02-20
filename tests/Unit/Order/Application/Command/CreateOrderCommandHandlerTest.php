<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Tests\Unit\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Application\Command\CreateOrderCommand;
use Deliverea\CoffeeMachine\Order\Application\Command\CreateOrderCommandHandler;
use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;
use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Order\Infrastructure\InMemoryOrderRepository;
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
        $orderId = OrderId::Create();
        $this->handler->handle(new CreateOrderCommand($orderId, false));
        $order = $this->orderRepository->getByIdOrFail($orderId);
        self::assertNotNull($order);
    }

    /**
     * @test
     */
    public function given_order_id_and_hot_when_handle_then_order_hot_should_be_created()
    {
        $orderId = OrderId::Create();
        $this->handler->handle(new CreateOrderCommand($orderId, true));
        $order = $this->orderRepository->getByIdOrFail($orderId);
        self::assertTrue($order->hot());
    }
}