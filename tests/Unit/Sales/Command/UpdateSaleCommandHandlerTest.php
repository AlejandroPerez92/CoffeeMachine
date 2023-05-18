<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Tests\Unit\Sales\Command;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command\UpdateSaleCommand;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command\UpdateSaleCommandHandler;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Order;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSale;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSaleRepositoryInterface;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure\InMemoryOrderRepository;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure\InMemoryProductSaleRepository;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use PHPUnit\Framework\TestCase;

final class UpdateSaleCommandHandlerTest extends TestCase
{
    private OrderRepositoryInterface $orderRepository;
    private ProductSaleRepositoryInterface $productSaleRepository;
    private UpdateSaleCommandHandler $commandHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clearRepos();
        $this->commandHandler = new UpdateSaleCommandHandler($this->orderRepository, $this->productSaleRepository);
    }

    /**
     * @test
     */
    public function given_order_paid_when_handle_then_create_order_sale()
    {
        $orderId = new OrderId('c772ed7a-0e47-4d29-ae5d-f25d1ca76ddf');;
        $this->orderRepository->save(new Order($orderId, [new OrderLine('tea', 50)], false));
        $this->commandHandler->handle(new UpdateSaleCommand($orderId->value()));
        $productSale = $this->productSaleRepository->getByProductNameOrFail('tea');
        self::assertEquals(50, $productSale->total());
        $this->clearRepos();
    }

    /**
     * @test
     */
    public function given_order_pending_when_handle_then_mark_order_as_paid()
    {
        $orderId = new OrderId('c772ed7a-0e47-4d29-ae5d-f25d1ca76ddf');;
        $this->orderRepository->save(new Order($orderId, [new OrderLine('tea', 50)], false));
        $this->commandHandler->handle(new UpdateSaleCommand($orderId->value()));
        $order = $this->orderRepository->getOrderOrFail($orderId);
        self::assertTrue($order->paid());
        $this->clearRepos();
    }

    /**
     * @test
     */
    public function given_order_paid_when_handle_then_product_sale_not_increment()
    {
        $orderId = new OrderId('c772ed7a-0e47-4d29-ae5d-f25d1ca76ddf');;
        $this->orderRepository->save(new Order($orderId, [new OrderLine('tea', 50)], true));
        $this->productSaleRepository->save(new ProductSale('tea',50));
        $this->commandHandler->handle(new UpdateSaleCommand($orderId->value()));
        $productSale = $this->productSaleRepository->getByProductNameOrFail('tea');
        self::assertEquals(50, $productSale->total());
        $this->clearRepos();
    }

    private function clearRepos()
    {
        $this->orderRepository = new InMemoryOrderRepository();
        $this->productSaleRepository = new InMemoryProductSaleRepository();
    }
}