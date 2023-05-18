<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Order\Domain\Exception\NotFoundProductException;
use AlexPerez\CoffeeMachine\Order\Domain\Product;
use AlexPerez\CoffeeMachine\Order\Domain\ProductRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final class InMemoryProductRepository implements ProductRepositoryInterface
{

    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function persist(Product $product)
    {
        $this->data[$product->name()] = [
            'price' => $product->price()->value(),
            'limit' => $product->limit()->value()
        ];
    }

    public function getByNameOrFail(string $name): Product
    {
        if (!isset($this->data[$name])) {
            throw NotFoundProductException::FromName($name);
        };

        $product = $this->data[$name];

        return new Product(
            $name, new Money($product['price']), new PositiveInteger($product['limit'])
        );
    }
}