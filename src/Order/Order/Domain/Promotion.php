<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain;

use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final class Promotion
{
    private string $applyProduct;
    private PositiveInteger $fromUnits;
    private string $productToAdd;

    public function __construct(string $applyProduct, PositiveInteger $fromUnits, string $productToAdd)
    {
        $this->applyProduct = $applyProduct;
        $this->fromUnits = $fromUnits;
        $this->productToAdd = $productToAdd;
    }

    public function applyProduct(): string
    {
        return $this->applyProduct;
    }

    public function fromUnits(): PositiveInteger
    {
        return $this->fromUnits;
    }

    public function productToAdd(): string
    {
        return $this->productToAdd;
    }

    public function isApplicable(OrderLine $orderLine): bool
    {
        return $orderLine->units()->moreThanOrEqual($this->fromUnits);
    }

}