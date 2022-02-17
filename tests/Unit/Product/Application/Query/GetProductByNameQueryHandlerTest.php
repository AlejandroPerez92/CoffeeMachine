<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Tests\Unit\Product\Application\Query;

use Deliverea\CoffeeMachine\Product\Application\Query\GetProductByNameQuery;
use Deliverea\CoffeeMachine\Product\Application\Query\GetProductByNameQueryHandler;
use Deliverea\CoffeeMachine\Product\Domain\Exception\NotFoundProductException;
use Deliverea\CoffeeMachine\Product\Domain\Product;
use Deliverea\CoffeeMachine\Product\Domain\ProductId;
use Deliverea\CoffeeMachine\Product\Domain\ProductRepositoryInterface;
use Deliverea\CoffeeMachine\Product\Infrastructure\InMemoryProductRepository;
use Deliverea\CoffeeMachine\Shared\Domain\Money\Money;
use PHPUnit\Framework\TestCase;

final class GetProductByNameQueryHandlerTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private GetProductByNameQueryHandler $queryHandler;
    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = new InMemoryProductRepository([
            '0339259c-9ea5-42b6-bcab-861776be81b9' => [
                'id' => '0339259c-9ea5-42b6-bcab-861776be81b9',
                'name' => 'Tea',
                'price' => 40,
                'date' => '2022-01-01 00:00:00'
            ]
        ]);

        $this->queryHandler = new GetProductByNameQueryHandler($this->productRepository);
    }

    /**
     * @test
     */
    public function given_exists_name_when_handle_then_return_response_without_error()
    {
        $response = $this->queryHandler->handle(new GetProductByNameQuery('Tea'));
        self::assertEquals("",$response->error());
        self::assertEquals("0339259c-9ea5-42b6-bcab-861776be81b9",$response->id());
    }
    /**
     * @test
     */
    public function given_not_exists_name_when_handle_then_return_response_with_error()
    {
        $response = $this->queryHandler->handle(new GetProductByNameQuery('Kukuxumusu'));
        self::assertEquals("Product with name Kukuxumusu not found",$response->error());
        self::assertEquals("0",$response->id());
    }
}