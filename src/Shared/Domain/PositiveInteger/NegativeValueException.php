<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Shared\Domain\PositiveInteger;

final class NegativeValueException extends \LogicException
{
    public function __construct(int $value)
    {
        parent::__construct(sprintf("The value %d is not positive",$value));
    }

}