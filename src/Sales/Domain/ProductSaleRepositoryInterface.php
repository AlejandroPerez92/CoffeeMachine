<?php

namespace Deliverea\CoffeeMachine\Sales\Domain;

interface ProductSaleRepositoryInterface
{
    public function getByProductNameOrFail(string $productName): ProductSale;
    public function getAllSales(): array;
    public function save(ProductSale $productSale);
}