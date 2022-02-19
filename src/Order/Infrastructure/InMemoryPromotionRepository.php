<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Infrastructure;

use Deliverea\CoffeeMachine\Order\Domain\Exception\PromotionNotFoundException;
use Deliverea\CoffeeMachine\Order\Domain\Promotion;
use Deliverea\CoffeeMachine\Order\Domain\PromotionRepositoryInterface;
use Deliverea\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

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