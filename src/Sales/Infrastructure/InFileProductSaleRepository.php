<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\Domain\ProductSale;
use AlexPerez\CoffeeMachine\Sales\Domain\ProductSaleRepositoryInterface;

final class InFileProductSaleRepository implements ProductSaleRepositoryInterface
{
    private string $fileRoute;
    private array $data = [];

    public function __construct(string $fileRoute)
    {
        $this->fileRoute = $fileRoute;
        $this->loadData();
    }

    public function getByProductNameOrFail(string $productName): ProductSale
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

    public function save(ProductSale $productSale)
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