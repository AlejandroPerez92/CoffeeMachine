<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller;

use AlexPerez\CoffeeMachine\Order\Order\Application\Command\CreateOrderCommand;
use AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Request\CreateOrderRequest;
use AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Response\CreateOrderResponse;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateOrderController
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    #[Route('/api/orders', name: 'create_order', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateOrderRequest $request): JsonResponse
    {
        $orderId = OrderId::create();
        $this->commandBus->handle(new CreateOrderCommand($orderId, $request->extraHot));

        return new JsonResponse(
            new CreateOrderResponse(
                $orderId->value(),
            ),
            Response::HTTP_CREATED
        );
    }
}
