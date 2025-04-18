<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Response;

readonly class ErrorResponse
{
    public function __construct(
        public string $error
    ) {
    }
}