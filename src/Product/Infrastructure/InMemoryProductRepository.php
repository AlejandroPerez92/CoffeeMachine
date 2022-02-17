<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Product\Infrastructure;

use Deliverea\CoffeeMachine\Product\Domain\Exception\NotFoundProductException;
use Deliverea\CoffeeMachine\Product\Domain\Product;
use Deliverea\CoffeeMachine\Product\Domain\ProductId;
use Deliverea\CoffeeMachine\Product\Domain\ProductRepositoryInterface;
use Deliverea\CoffeeMachine\Shared\Domain\Money\Money;

final class InMemoryProductRepository implements ProductRepositoryInterface
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getByNameOrFail(string $name): Product
    {
        /** @var Product $product */
        foreach ($this->data as $product){
            if($product['name'] === $name){
                return new Product(
                    new ProductId($product['id']), $product['name'], new Money($product['price']), new \DateTimeImmutable($product['date'])
                );
            };
        }

        throw NotFoundProductException::FromName($name);
    }

    public function persist(Product $product)
    {
        $this->data[$product->id()->value()] = $product;
    }
}