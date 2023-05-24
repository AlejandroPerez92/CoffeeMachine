<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSales;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSalesRepositoryInterface;

final class AccountProductCommandHandler
{

    public function __construct(private readonly ProductSalesRepositoryInterface $repository)
    {
    }

    public function handle(AccountProductCommand $command): void
    {
        try {
            $productSale = $this->repository->getByProductNameOrFail($command->product);
        } catch (NotFoundSalesException) {
            $productSale = new ProductSales($command->product, 0);
        }

        $productSale->incrementTotal($command->total);
        $this->repository->save($productSale);
    }

}