<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Tests\Unit\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Application\Command\PayOrderCommand;
use Deliverea\CoffeeMachine\Order\Application\Command\PayOrderCommandHandler;
use Deliverea\CoffeeMachine\Order\Domain\Exception\NotEnoughAmountToPayOrder;
use Deliverea\CoffeeMachine\Order\Domain\Order;
use Deliverea\CoffeeMachine\Order\Domain\OrderId;
use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Order\Infrastructure\InMemoryOrderRepository;
use Deliverea\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;
use Deliverea\CoffeeMachine\Shared\Domain\Money\Money;
use Deliverea\CoffeeMachine\Shared\Infrastructure\Eventbus\EventBusWrapper;
use Deliverea\CoffeeMachine\Tests\Unit\Utils\EventBusTest;
use PHPUnit\Framework\TestCase;

final class PayOrderLineCommandHandlerTest extends TestCase
{
    private OrderRepositoryInterface $orderRepository;
    private PayOrderCommandHandler $handler;
    private EventBusInterface $eventBus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = new InMemoryOrderRepository();
        $this->clearOrderRepo();
        $this->eventBus = new EventBusTest();
        $this->handler = new PayOrderCommandHandler($this->orderRepository, $this->eventBus);
    }

    /**
     * @test
     */
    public function given_order_and_amount_when_handle_then_order_should_be_paid()
    {
        $this->handler->handle(new PayOrderCommand(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'), 40));
        $order = $this->orderRepository->getByIdOrFail(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        self::assertTrue($order->paid());
    }

    /**
     * @test
     */
    public function given_order_and_less_amount_when_handle_then_exception_should_be_rose()
    {
        $this->expectException(NotEnoughAmountToPayOrder::class);
        $this->handler->handle(new PayOrderCommand(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'), 39));
        $order = $this->orderRepository->getByIdOrFail(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'));
    }

    private function clearOrderRepo()
    {
        $this->orderRepository = new InMemoryOrderRepository([
            '45b4b0e2-4acc-45a2-8276-a73e44a66576' => new Order(
                new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'),
                [],
                new Money(40),
                new \DateTimeImmutable(),
                false,
                false)
        ]);
    }
}