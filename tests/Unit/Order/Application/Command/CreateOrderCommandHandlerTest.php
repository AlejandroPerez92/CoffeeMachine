<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Tests\Unit\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Application\Command\CreateOrderCommand;
use Deliverea\CoffeeMachine\Order\Application\Command\CreateOrderCommandHandler;
use Deliverea\CoffeeMachine\Order\Domain\OrderId;
use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Order\Infrastructure\InMemoryOrderRepository;
use Deliverea\CoffeeMachine\Shared\Domain\Uuid\Uuid;
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
        $this->handler->handle(new CreateOrderCommand($orderId));
        $order = $this->orderRepository->getByIdOrFail($orderId);
        self::assertNotNull($order);
    }
}