<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Domain;

interface ProductRepositoryInterface
{
    public function getByNameOrFail(string $name): Product;
}