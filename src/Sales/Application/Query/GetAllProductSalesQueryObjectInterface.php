<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Application\Query;

use Deliverea\CoffeeMachine\Sales\Domain\ProductSale;

interface GetAllProductSalesQueryObjectInterface
{
    public function fill(ProductSale ...$productSales): void;
}