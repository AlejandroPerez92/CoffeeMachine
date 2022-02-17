<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Product\Domain;

use Deliverea\CoffeeMachine\Shared\Domain\Money\Money;

final class Product
{
    private ProductId $id;
    private string $name;
    private Money $price;
    private \DateTimeImmutable $created;

    public function __construct(ProductId $id, string $name, Money $price, \DateTimeImmutable $created)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->created = $created;
    }

    public static function Create(string $name, Money $price): self
    {
        $product = new self(ProductId::Create(),$name,$price,new \DateTimeImmutable());
        // TODO Dispatch domain event
        return $product;
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function created(): \DateTimeImmutable
    {
        return $this->created;
    }
}