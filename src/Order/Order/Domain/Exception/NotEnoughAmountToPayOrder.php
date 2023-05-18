<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain\Exception;

use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;

final class NotEnoughAmountToPayOrder extends \LogicException
{
    private string $cost;

    public function __construct(Money $total, Money $amount)
    {
        $this->cost = (string)$total->toFloat();
        parent::__construct(sprintf("Provided %d insufficient for pay %d",$total->value(), $amount->value()));
    }

    public function cost(): string
    {
        return $this->cost;
    }

}