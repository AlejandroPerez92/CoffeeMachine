<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\PromotionNotFoundException;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Promotion;
use AlexPerez\CoffeeMachine\Order\Order\Domain\PromotionRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final class InMemoryPromotionRepository implements PromotionRepositoryInterface
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getByApplyProductNameOrFail(string $productName): Promotion
    {
        foreach ($this->data as $promotion) {
            if ($promotion['applyProduct'] === $productName){
                return new Promotion($promotion['applyProduct'], new PositiveInteger($promotion['fromUnits']),$promotion['productToAdd']);
            }
        }

        throw new PromotionNotFoundException();
    }
}