<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Query;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSale;

interface GetAllProductSalesQueryObjectInterface
{
    public function fill(ProductSale ...$productSales): void;
}