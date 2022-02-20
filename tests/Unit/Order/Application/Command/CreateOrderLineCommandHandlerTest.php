<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Tests\Unit\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Application\Command\CreateOrderLineCommand;
use Deliverea\CoffeeMachine\Order\Application\Command\CreateOrderLineCommandHandler;
use Deliverea\CoffeeMachine\Order\Domain\Exception\LimitUnitsException;
use Deliverea\CoffeeMachine\Order\Domain\Order;
use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;
use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Order\Domain\ProductRepositoryInterface;
use Deliverea\CoffeeMachine\Order\Domain\PromotionRepositoryInterface;
use Deliverea\CoffeeMachine\Order\Infrastructure\InMemoryOrderRepository;
use Deliverea\CoffeeMachine\Order\Infrastructure\InMemoryProductRepository;
use Deliverea\CoffeeMachine\Order\Infrastructure\InMemoryPromotionRepository;
use Deliverea\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;
use Deliverea\CoffeeMachine\Shared\Domain\Money\Money;
use Deliverea\CoffeeMachine\Tests\Unit\Utils\EventBusTest;
use PHPUnit\Framework\TestCase;

final class CreateOrderLineCommandHandlerTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private OrderRepositoryInterface $orderRepository;
    private PromotionRepositoryInterface $promotionRepository;
    private CreateOrderLineCommandHandler $handler;
    private EventBusInterface $eventBus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = new InMemoryProductRepository([
            'tea'   => [
                'price' => 40,
                'limit' => 1
            ],
            'sugar' => [
                'price' => 0,
                'limit' => 2
            ],
            'stick' => [
                'price' => 0,
                'limit' => 1
            ]
        ]);

        $this->clearOrderRepo();

        $this->promotionRepository = new InMemoryPromotionRepository([
            [
                'applyProduct' => 'sugar',
                'fromUnits'    => 1,
                'productToAdd' => 'stick'
            ]
        ]);

        $this->eventBus = new EventBusTest();

        $this->handler = new CreateOrderLineCommandHandler(
            $this->productRepository,
            $this->promotionRepository,
            $this->orderRepository,
            $this->eventBus
        );
    }

    /**
     * @test
     */
    public function given_line_when_handle_then_add_line_to_order()
    {
        $this->handler->handle(new CreateOrderLineCommand('tea', 1, '45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        $order = $this->orderRepository->getByIdOrFail(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        self::assertCount(1, $order->lines());
        $this->clearOrderRepo();
    }

    /**
     * @test
     */
    public function given_line_with_more_units_than_limit_when_handle_then_exception_rise()
    {
        $this->expectException(LimitUnitsException::class);
        $this->handler->handle(new CreateOrderLineCommand('sugar', 3, '45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        $this->orderRepository->getByIdOrFail(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        $this->clearOrderRepo();
    }

    /**
     * @test
     */
    public function given_line_with_promotion_when_handle_then_promotion_line_should_be_add()
    {
        $this->handler->handle(new CreateOrderLineCommand('sugar', 2, '45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        $order = $this->orderRepository->getByIdOrFail(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        self::assertCount(2, $order->lines());
        $this->clearOrderRepo();
    }

    /**
     * @test
     */
    public function given_line_when_handle_then_total_should_be_the_value_of_the_line()
    {
        $this->handler->handle(new CreateOrderLineCommand('tea', 1, '45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        $order = $this->orderRepository->getByIdOrFail(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        self::assertEquals(40, $order->total()->value());
        $this->clearOrderRepo();
    }

    /**
     * @test
     */
    public function given_line_with_0_units_when_handle_then_line_not_be_added()
    {
        $this->handler->handle(new CreateOrderLineCommand('sugar', 0, '45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        $order = $this->orderRepository->getByIdOrFail(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        self::assertCount(0, $order->lines());
        $this->clearOrderRepo();
    }

    /**
     * @test
     */
    public function given_line_when_handle_then_should_dispatch_event()
    {
        $this->handler->handle(new CreateOrderLineCommand('tea', 1, '45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        $order = $this->orderRepository->getByIdOrFail(new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'));
        self::assertTrue($this->eventBus->hasEvents('order.line.added'));
    }

    private function clearOrderRepo()
    {
        $this->orderRepository = new InMemoryOrderRepository([
            '45b4b0e2-4acc-45a2-8276-a73e44a66576' => new Order(
                new OrderId('45b4b0e2-4acc-45a2-8276-a73e44a66576'),
                [],
                new Money(0),
                new \DateTimeImmutable(),
                false,
                false)
        ]);
    }


}