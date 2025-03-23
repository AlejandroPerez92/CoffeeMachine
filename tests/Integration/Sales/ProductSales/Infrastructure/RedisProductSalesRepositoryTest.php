<?php

namespace AlexPerez\CoffeeMachine\Tests\Integration\Sales\ProductSales\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSales;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure\RedisProductSalesRepository;
use AlexPerez\CoffeeMachine\Tests\Integration\IntegrationTestCase;

class RedisProductSalesRepositoryTest extends IntegrationTestCase
{
    private RedisProductSalesRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getContainer()->get(RedisProductSalesRepository::class);
    }

    public function testSaveAndRetrieveProductSales()
    {
        $saleData = new ProductSales(
            'Coffee',
            10,
        );
        $this->repository->save($saleData);

        $retrievedSale = $this->repository->getByProductNameOrFail($saleData->name());
        $this->assertEquals($saleData, $retrievedSale);
    }

    public function testGetAllSales()
    {
        $coffeeSaleData = new ProductSales(
            'Coffee',
            10,
        );
        $teaSaleData = new ProductSales(
            'Tea',
            30,
        );
        $this->repository->save($coffeeSaleData);
        $this->repository->save($teaSaleData);

        $allSales = $this->repository->getAllSales();
        $this->assertCount(2, $allSales);
    }

    public function testGetByProductNameOrFailThrowsExceptionWhenNotFound()
    {
        $this->expectException(NotFoundSalesException::class);
        $this->repository->getByProductNameOrFail('NonExistentProduct');
    }

    public function testOverrideProductSales()
    {
        $coffeeSaleData = new ProductSales(
            'Coffee',
            10,
        );
        $this->repository->save($coffeeSaleData);
        $coffeeSaleData = new ProductSales(
            'Coffee',
            15,
        );
        $this->repository->save($coffeeSaleData);

        $updatedSale = $this->repository->getByProductNameOrFail($coffeeSaleData->name());
        $this->assertEquals($coffeeSaleData, $updatedSale);
    }
}
