<?php

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Domain;

interface ProductSalesRepositoryInterface
{
    public function getByProductNameOrFail(string $productName): ProductSales;
    public function getAllSales(): array;
    public function save(ProductSales $productSale);
}