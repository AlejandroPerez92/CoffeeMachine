<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\App\Command;

final class ServiceTest
{
    public function call(): string
    {
        return "service working";
    }
}