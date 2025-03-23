<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSales;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSalesRepositoryInterface;

final class InFileProductSalesRepository implements ProductSalesRepositoryInterface
{
    private array $data = [];

    public function __construct(private readonly string $fileRoute)
    {
        $this->loadData();
    }

    public function getByProductNameOrFail(string $productName): ProductSales
    {
        $this->loadData();

        if (!isset($this->data[$productName])) {
            throw new NotFoundSalesException($productName);
        };

        return $this->data[$productName];
    }

    public function getAllSales(): array
    {
        $this->loadData();
        return $this->data;
    }

    public function save(ProductSales $productSale): void
    {
        $this->loadData();
        $this->data[$productSale->name()] = $productSale;
        $this->saveData();
    }

    private function saveData(): void
    {
        file_put_contents($this->fileRoute, serialize($this->data));
    }

    private function loadData(): void
    {
        try{
            $fileData = @file_get_contents($this->fileRoute);
            if(!$fileData){
                throw new \RuntimeException('File not found');
            }
            $this->data = unserialize($fileData);
        }catch (\Exception $e){
            $this->saveData();
            $this->data = [];
        }
    }
}