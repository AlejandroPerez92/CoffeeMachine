<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSales;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSalesRepositoryInterface;

final class InFileProductSalesRepository implements ProductSalesRepositoryInterface
{
    private string $fileRoute;
    private array $data = [];

    public function __construct(string $fileRoute)
    {
        $this->fileRoute = $fileRoute;
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

    public function save(ProductSales $productSale)
    {
        $this->loadData();
        $this->data[$productSale->name()] = $productSale;
        $this->saveData();
    }

    private function saveData()
    {
        file_put_contents($this->fileRoute, serialize($this->data));
    }

    private function loadData()
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