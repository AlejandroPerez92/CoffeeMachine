<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Query;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSales;

interface GetAllProductSalesQueryObjectInterface
{
    public function fill(ProductSales ...$productSales): void;
}