<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Domain\Uuid;

final class NotValidUuidException extends \LogicException
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf("The %s is not a valid id value", $id));
    }

}