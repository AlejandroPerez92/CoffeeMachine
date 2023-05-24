<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSales;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSalesRepositoryInterface;

final class InMemoryProductSalesRepository implements ProductSalesRepositoryInterface
{
    private array $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function getByProductNameOrFail(string $productName): ProductSales
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

    public function save(ProductSales $productSale)
    {
        $this->data[$productSale->name()] = $productSale;
    }
}