<?php

namespace Deliverea\CoffeeMachine\Product\Domain;

interface ProductRepositoryInterface
{
    public function getByNameOrFail(string $name): Product;
    public function persist(Product $product);
}