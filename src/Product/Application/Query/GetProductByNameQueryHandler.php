<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Product\Application\Query;

final class GetProductByNameQueryHandler
{
    public function handle(GetProductByNameQuery $query): string
    {
        return "Soy el handler";
    }
}