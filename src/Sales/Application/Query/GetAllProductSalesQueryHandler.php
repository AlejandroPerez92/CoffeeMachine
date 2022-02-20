<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Application\Query;

use Deliverea\CoffeeMachine\Sales\Domain\ProductSaleRepositoryInterface;

final class GetAllProductSalesQueryHandler
{
    private ProductSaleRepositoryInterface $productSaleRepository;

    public function __construct(ProductSaleRepositoryInterface $productSaleRepository)
    {
        $this->productSaleRepository = $productSaleRepository;
    }

    public function handle(GetAllProductSalesQueryObjectInterface $object): GetAllProductSalesQueryObjectInterface
    {
        $sales = $this->productSaleRepository->getAllSales();
        $object->fill(...$sales);
        return $object;
    }
}