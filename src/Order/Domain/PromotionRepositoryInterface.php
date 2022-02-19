<?php

namespace Deliverea\CoffeeMachine\Order\Domain;

interface PromotionRepositoryInterface
{
    public function getByApplyProductNameOrFail(string $productName): Promotion;
}