<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Domain\Exception\PromotionNotFoundException;
use Deliverea\CoffeeMachine\Order\Domain\OrderId;
use Deliverea\CoffeeMachine\Order\Domain\OrderLine;
use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Order\Domain\ProductRepositoryInterface;
use Deliverea\CoffeeMachine\Order\Domain\PromotionRepositoryInterface;
use Deliverea\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;
use Deliverea\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

final class CreateOrderLineCommandHandler
{
    private ProductRepositoryInterface $productRepository;
    private PromotionRepositoryInterface $promotionRepository;
    private OrderRepositoryInterface $orderRepository;
    private EventBusInterface $eventBus;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        PromotionRepositoryInterface $promotionRepository,
        OrderRepositoryInterface $orderRepository,
        EventBusInterface $eventBus)
    {
        $this->productRepository = $productRepository;
        $this->promotionRepository = $promotionRepository;
        $this->orderRepository = $orderRepository;
        $this->eventBus = $eventBus;
    }

    public function handle(CreateOrderLineCommand $command): void
    {
        $order = $this->orderRepository->getByIdOrFail(new OrderId($command->orderId()));
        $orderLine = $this->createOrderLine($command->productName(),new PositiveInteger($command->units()));
        $order->addLine($orderLine);

        try{
            $promo = $this->promotionRepository->getByApplyProductNameOrFail($command->productName());
            if($promo->isApplicable($orderLine)){
                $order->addLine($this->createOrderLine($promo->productToAdd(),new PositiveInteger(1)));
            }
        }catch (PromotionNotFoundException $e){

        }

        $this->orderRepository->save($order);

        $this->eventBus->publish(...$order->pullDomainEvents());
    }

    private function createOrderLine(string $productName, PositiveInteger $units): OrderLine
    {
        $product = $this->productRepository->getByNameOrFail($productName);

        return OrderLine::Create($product, $units);
    }
}