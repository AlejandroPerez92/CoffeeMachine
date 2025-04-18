<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller;

use AlexPerez\CoffeeMachine\Order\Order\Application\Command\PayOrderCommand;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\NotEnoughAmountToPayOrder;
use AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Request\PayOrderRequest;
use AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Response\ErrorResponse;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class PayOrderController
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    #[Route('/api/orders/{orderId}/payment', name: 'pay_order', methods: ['POST'])]
    public function __invoke(string $orderId, #[MapRequestPayload] PayOrderRequest $request): Response
    {
        try {
            $this->commandBus->handle(new PayOrderCommand(OrderId::fromString($orderId), $request->amount));
        } catch (NotEnoughAmountToPayOrder $e) {
            return new JsonResponse(
                new ErrorResponse(
                    sprintf('Not enough amount to pay the order. The order costs %s.', $e->cost())
                ),
                Response::HTTP_BAD_REQUEST
            );
        }

        return new Response(
            status: Response::HTTP_ACCEPTED,
        );
    }
}
