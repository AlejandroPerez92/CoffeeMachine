<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller;

use AlexPerez\CoffeeMachine\Order\Order\Application\Command\CreateOrderLineCommand;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\LimitUnitsException;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\NotFoundProductException;
use AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Request\AddOrderLineRequest;
use AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Response\AddOrderLineResponse;
use AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Response\ErrorResponse;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\NegativeValueException;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class AddOrderLineController
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    #[Route('/api/orders/{orderId}/lines', name: 'add_order_line', methods: ['POST'])]
    public function __invoke(string $orderId, #[MapRequestPayload] AddOrderLineRequest $request): Response
    {
        $productName = $request->productName();
        $units = $request->units();

        try {
            $this->commandBus->handle(new CreateOrderLineCommand($productName, $units, $orderId));
        } catch (NotFoundProductException $e) {
            return new JsonResponse(
                new ErrorResponse('The product type should be tea, coffee, chocolate or sugar.'),
                Response::HTTP_BAD_REQUEST
            );
        } catch (LimitUnitsException|NegativeValueException $e) {
            return new JsonResponse(
                new ErrorResponse('The number of units should be between 1 and 5.'),
                Response::HTTP_BAD_REQUEST
            );
        }

        return new Response(status: Response::HTTP_CREATED);
    }
}
