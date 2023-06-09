<?php

namespace AlexPerez\CoffeeMachine\Order\Order\Domain;

interface PromotionRepositoryInterface
{
    public function getByApplyProductNameOrFail(string $productName): Promotion;
}