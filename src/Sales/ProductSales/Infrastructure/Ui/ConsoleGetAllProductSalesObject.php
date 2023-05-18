<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure\Ui;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Query\GetAllProductSalesQueryObjectInterface;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSale;

final class ConsoleGetAllProductSalesObject implements GetAllProductSalesQueryObjectInterface
{
    private array $productSales = [];

    public function fill(ProductSale ...$productSales): void
    {
        foreach ($productSales as $productSale) {
            $this->productSales[$productSale->name()] = $productSale->total();
        }
    }

    public function getString(): string
    {
        $response = '';
        foreach ($this->productSales as $product => $total) {
            $response .= "$product => $total" . PHP_EOL;
        }

        return $response;
    }
}