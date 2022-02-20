<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Tests\Unit\Sales\Command;

use Deliverea\CoffeeMachine\Sales\Application\Command\UpdateSaleCommand;
use Deliverea\CoffeeMachine\Sales\Domain\OrderLine;
use Deliverea\CoffeeMachine\Sales\Application\Command\UpdateSaleCommandHandler;
use Deliverea\CoffeeMachine\Sales\Domain\Order;
use Deliverea\CoffeeMachine\Sales\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Sales\Domain\ProductSale;
use Deliverea\CoffeeMachine\Sales\Domain\ProductSaleRepositoryInterface;
use Deliverea\CoffeeMachine\Sales\Infrastructure\InMemoryOrderRepository;
use Deliverea\CoffeeMachine\Sales\Infrastructure\InMemoryProductSaleRepository;
use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;
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