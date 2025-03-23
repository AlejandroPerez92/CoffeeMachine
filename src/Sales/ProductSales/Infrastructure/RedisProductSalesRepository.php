<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSales;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSalesRepositoryInterface;
use Predis\Client;

final readonly class RedisProductSalesRepository implements ProductSalesRepositoryInterface
{

    public function __construct(private Client $redis)
    {
    }

    public function getByProductNameOrFail(string $productName): ProductSales
    {
        $total = $this->redis->hGet('product_sales', $productName);

        if (null === $total) {
            throw new NotFoundSalesException($productName);
        }

        return new ProductSales($productName, (int)$total);
    }

    public function getAllSales(): array
    {
        $data = $this->redis->hGetAll('product_sales');
        $sales = [];

        foreach ($data as $name => $total) {
            $sales[] = new ProductSales($name, (int)$total);
        }

        return $sales;
    }

    public function save(ProductSales $productSale): void
    {
        $this->redis->hSet('product_sales', $productSale->name(), (string)$productSale->total());
    }
}