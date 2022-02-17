<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Product\Application\Query;

final class GetProductByNameResponse
{
    private string $error;
    private string $id;

    public function __construct(string $error, string $id)
    {
        $this->error = $error;
        $this->id = $id;
    }

    public function error(): string
    {
        return $this->error;
    }

    public function id(): string
    {
        return $this->id;
    }

}