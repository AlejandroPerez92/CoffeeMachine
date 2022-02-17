<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Product\Application\Query;

final class GetProductByNameQuery
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

}