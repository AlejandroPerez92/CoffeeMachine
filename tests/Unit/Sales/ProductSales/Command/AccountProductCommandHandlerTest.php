<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Tests\Unit\Sales\ProductSales\Command;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command\AccountProductCommand;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command\AccountProductCommandHandler;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSales;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSalesRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class AccountProductCommandHandlerTest extends TestCase
{
    private ProductSalesRepositoryInterface $productSaleRepository;
    private AccountProductCommandHandler $commandHandler;

    protected function setUp(): void
    {
        $this->commandHandler = new AccountProductCommandHandler(
            $this->productSaleRepository = $this->createMock(ProductSalesRepositoryInterface::class)
        );
    }

    /**
     * @test
     */
    public function given_new_product_sales_when_handle_then_create_product_sales()
    {
        $expectSales = new ProductSales(
            'tea',
            50,
        );

        $this->productSaleRepository
            ->expects($this->once())
            ->method('getByProductNameOrFail')
            ->willThrowException(new NotFoundSalesException('tea'));
        $this->productSaleRepository
            ->expects($this->once())
            ->method('save')
            ->with($expectSales);

        $this->commandHandler->handle(new AccountProductCommand('tea', 50));
    }

    /**
     * @test
     */
    public function given_exist_order_sales_when_handle_then_increment_total()
    {
        $productSales = new ProductSales(
            'tea',
            50,
        );

        $expectProductSales = new ProductSales(
            'tea',
            100,
        );

        $this->productSaleRepository
            ->expects($this->once())
            ->method('getByProductNameOrFail')
            ->with('tea')
            ->willReturn($productSales);

        $this->productSaleRepository
            ->expects($this->once())
            ->method('save')
            ->with($expectProductSales);

        $this->commandHandler->handle(new AccountProductCommand('tea', 50));
    }
}