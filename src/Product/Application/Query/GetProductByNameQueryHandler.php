<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Product\Application\Query;

final class GetProductByNameQueryHandler
{
    public function handle(GetProductByNameQuery $query): GetProductByNameResponse
    {
        if (!in_array($query->name(), ['tea', 'coffee', 'chocolate'])) {
            return new GetProductByNameResponse('The drink type should be tea, coffee or chocolate.','1');
        }

        return new GetProductByNameResponse('','0');
    }
}