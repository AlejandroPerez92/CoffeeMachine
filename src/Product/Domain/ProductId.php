<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Product\Domain;

use Deliverea\CoffeeMachine\Shared\Domain\Uuid\Uuid;

final class ProductId
{
    private Uuid $id;

    public function __construct(string $id)
    {
        $this->id = new Uuid($id);
    }

    public static function Create(): self
    {
        return new ProductId((string) Uuid::Create());
    }

    public function value(): string
    {
        return (string) $this->id;
    }
}