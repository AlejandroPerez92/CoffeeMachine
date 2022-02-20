<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Tests\Unit\Sales\Command;

use Deliverea\CoffeeMachine\Sales\Application\Command\UpdateOrderLinesCommand;
use Deliverea\CoffeeMachine\Sales\Application\Command\UpdateOrderLinesCommandHandler;
use Deliverea\CoffeeMachine\Sales\Domain\Order;
use Deliverea\CoffeeMachine\Sales\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Sales\Infrastructure\InMemoryOrderRepository;
use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;
use PHPUnit\Framework\TestCase;

final class UpdateOrderLinesCommandHandlerTest extends TestCase
{
    private OrderRepositoryInterface $orderRepository;
    private UpdateOrderLinesCommandHandler $commandHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clearOrderRepo();
        $this->commandHandler = new UpdateOrderLinesCommandHandler($this->orderRepository);
    }

    /**
     * @test
     */
    public function given_not_exists_order_when_handle_then_new_order_should_be_created()
    {
        $orderId = new OrderId('c772ed7a-0e47-4d29-ae5d-f25d1ca76ddf');
        $this->commandHandler->handle(new UpdateOrderLinesCommand($orderId->value(),'tea',50));
        $order = $this->orderRepository->getOrderOrFail($orderId);
        self::assertNotNull($order);
        $this->clearOrderRepo();
    }

    /**
     * @test
     */
    public function given_line_to_update_when_handle_then_order_should_have_a_line()
    {
        $orderId = new OrderId('c772ed7a-0e47-4d29-ae5d-f25d1ca76ddf');
        $this->orderRepository->save(new Order($orderId,[],false));
        $this->commandHandler->handle(new UpdateOrderLinesCommand($orderId->value(),'tea',50));
        $order = $this->orderRepository->getOrderOrFail($orderId);
        self::assertCount(1,$order->lines());
        $this->clearOrderRepo();
    }

    public function clearOrderRepo()
    {
        $this->orderRepository = new InMemoryOrderRepository();
    }
}