<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Infrastructure\Ui;

use Deliverea\CoffeeMachine\Sales\Application\Query\GetAllProductSalesQueryObjectInterface;
use Deliverea\CoffeeMachine\Sales\Domain\ProductSale;

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