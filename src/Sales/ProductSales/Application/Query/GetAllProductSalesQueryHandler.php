<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Query;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSalesRepositoryInterface;

final class GetAllProductSalesQueryHandler
{
    public function __construct(private readonly ProductSalesRepositoryInterface $productSaleRepository)
    {
    }

    public function handle(GetAllProductSalesQueryObjectInterface $object): GetAllProductSalesQueryObjectInterface
    {
        $sales = $this->productSaleRepository->getAllSales();
        $object->fill(...$sales);
        return $object;
    }
}