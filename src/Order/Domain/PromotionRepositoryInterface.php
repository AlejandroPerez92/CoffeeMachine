<?php

namespace AlexPerez\CoffeeMachine\Order\Domain;

interface PromotionRepositoryInterface
{
    public function getByApplyProductNameOrFail(string $productName): Promotion;
}