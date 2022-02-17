<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Product\Application\Query;

use Deliverea\CoffeeMachine\Product\Domain\Exception\NotFoundProductException;
use Deliverea\CoffeeMachine\Product\Domain\ProductRepositoryInterface;

final class GetProductByNameQueryHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function handle(GetProductByNameQuery $query): GetProductByNameResponse
    {
        try{
            $product = $this->productRepository->getByNameOrFail($query->name());
            return new GetProductByNameResponse('',$product->id()->value());
        }catch (NotFoundProductException $e){
            return new GetProductByNameResponse($e->getMessage(),'0');
        }
    }
}