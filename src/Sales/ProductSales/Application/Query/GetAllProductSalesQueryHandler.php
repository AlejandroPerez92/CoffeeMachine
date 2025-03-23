<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Query;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSalesRepositoryInterface;

final readonly class GetAllProductSalesQueryHandler
{
    public function __construct(private ProductSalesRepositoryInterface $productSaleRepository)
    {
    }

    public function handle(GetAllProductSalesQueryObjectInterface $object): GetAllProductSalesQueryObjectInterface
    {
        $sales = $this->productSaleRepository->getAllSales();
        $object->fill(...$sales);
        return $object;
    }
}