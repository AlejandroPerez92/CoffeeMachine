<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSale;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSaleRepositoryInterface;

final class InMemoryProductSaleRepository implements ProductSaleRepositoryInterface
{
    private array $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function getByProductNameOrFail(string $productName): ProductSale
    {
        if (!isset($this->data[$productName])) {
            throw new NotFoundSalesException($productName);
        };

        return $this->data[$productName];
    }

    public function getAllSales(): array
    {
        return $this->data;
    }

    public function save(ProductSale $productSale)
    {
        $this->data[$productSale->name()] = $productSale;
    }
}