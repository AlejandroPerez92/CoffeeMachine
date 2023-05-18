<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Application\Command;

use AlexPerez\CoffeeMachine\Sales\Domain\Exception\NotFoundSalesException;
use AlexPerez\CoffeeMachine\Sales\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Sales\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Sales\Domain\ProductSale;
use AlexPerez\CoffeeMachine\Sales\Domain\ProductSaleRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

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
        $order = $this->orderRepository->getOrderOrFail(new OrderId($command->orderId()));

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