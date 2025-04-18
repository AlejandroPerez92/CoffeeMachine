<?php

namespace AlexPerez\CoffeeMachine\Tests\Integration\Order\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Product;
use AlexPerez\CoffeeMachine\Order\Order\Infrastructure\MongoDBOrderRepository;
use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;
use AlexPerez\CoffeeMachine\Tests\Integration\IntegrationTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;

class MongoDBOrderRepositoryTest extends IntegrationTestCase
{
    private MongoDBOrderRepository $repository;
    private DocumentManager $documentManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getContainer()->get('alex-perez.order.repository');
        $this->documentManager = $this->getContainer()->get(DocumentManager::class);
    }

    public function testSaveAndRetrieveOrder()
    {
        $order = $this->createOrder();
        $this->repository->save($order);

        $this->documentManager->clear();
        $retrievedOrder = $this->repository->getByIdOrFail($order->id());
        $this->assertEquals($order->id()->value(), $retrievedOrder->id()->value());
        $this->assertCount(0, $retrievedOrder->lines());
        $this->assertEquals(500, $retrievedOrder->total()->value());
        $this->assertFalse($retrievedOrder->paid());
    }

    public function testGetByIdOrFailThrowsExceptionWhenNotFound()
    {
        $this->expectException(NotFoundOrderException::class);
        $this->repository->getByIdOrFail(OrderId::Create());
    }

    public function testUpdateOrder()
    {
        // Create and save an initial order
        $order = Order::create(OrderId::create(),false);
        $this->repository->save($order);

        // Retrieve the order
        $retrievedOrder = $this->repository->getByIdOrFail($order->id());

        // Add a line to the order
        $product = $this->createProduct('Coffee', 250, 5);
        $orderLine = OrderLine::Create($product, new PositiveInteger(2));
        $retrievedOrder->addLine($orderLine);
        $retrievedOrder->pay(new Money(500));

        $this->repository->save($retrievedOrder);

        $this->documentManager->clear();
        $updatedOrder = $this->repository->getByIdOrFail($order->id());
        $this->assertEquals($order->id()->value(), $updatedOrder->id()->value());
        $this->assertCount(1, $updatedOrder->lines());
        $this->assertTrue($updatedOrder->paid());
        $this->assertEquals(500, $updatedOrder->total()->value()); // 2 units * 250 cents

        $this->repository->save($updatedOrder);
    }

    public function testFindAll()
    {
        // Create and save multiple orders
        $order1 = $this->createOrder();
        $order2 = $this->createOrder();
        $this->repository->save($order1);
        $this->repository->save($order2);

        // Retrieve all orders
        $allOrders = $this->repository->findAll();

        // Verify that the orders are in the result
        $this->assertGreaterThanOrEqual(2, count($allOrders));

        // Verify that our orders are in the result
        $foundOrder1 = false;
        $foundOrder2 = false;
        foreach ($allOrders as $order) {
            if ($order->id()->value() === $order1->id()->value()) {
                $foundOrder1 = true;
            }
            if ($order->id()->value() === $order2->id()->value()) {
                $foundOrder2 = true;
            }
        }
        $this->assertTrue($foundOrder1);
        $this->assertTrue($foundOrder2);
    }

    private function createOrder(): Order
    {
        return new Order(
            OrderId::Create(),
            [],
            new Money(500),
            new \DateTimeImmutable(),
            false,
            false
        );
    }

    private function createProduct(string $name, int $price, int $limit): Product
    {
        return new Product(
            $name,
            new Money($price),
            new PositiveInteger($limit)
        );
    }
}
