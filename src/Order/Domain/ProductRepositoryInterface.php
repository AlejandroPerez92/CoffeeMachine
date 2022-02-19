<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Domain;

interface ProductRepositoryInterface
{
    public function getByNameOrFail(string $name): Product;
}