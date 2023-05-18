<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Application\Query;

use AlexPerez\CoffeeMachine\Sales\Domain\ProductSale;

interface GetAllProductSalesQueryObjectInterface
{
    public function fill(ProductSale ...$productSales): void;
}