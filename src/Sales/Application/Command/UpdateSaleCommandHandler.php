<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Application\Command;

use Deliverea\CoffeeMachine\Sales\Domain\Exception\NotFoundSalesException;
use Deliverea\CoffeeMachine\Sales\Domain\OrderLine;
use Deliverea\CoffeeMachine\Sales\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Sales\Domain\ProductSale;
use Deliverea\CoffeeMachine\Sales\Domain\ProductSaleRepositoryInterface;

final class UpdateSaleCommandHandler
{
    private OrderRepositoryInterface $orderRepository;
    private ProductSaleRepositoryInterface $productSaleRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductSaleRepositoryInterface $productSaleRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productSaleRepository = $productSaleRepository;
    }

    public function handle(UpdateSaleCommand $command)
    {
        $order = $this->orderRepository->getOrderOrFail($command->orderId());

        if($order->paid()){
            return;
        }

        /** @var OrderLine $line */
        foreach ($order->lines() as $line) {
            try {
                $productSale = $this->productSaleRepository->getByProductNameOrFail($line->productName());
            } catch (NotFoundSalesException) {
                $productSale = new ProductSale($line->productName(),0);
            }
            $productSale->incrementTotal($line->total());
            $this->productSaleRepository->save($productSale);
        }

        $order->pay();

        $this->orderRepository->save($order);
    }
}