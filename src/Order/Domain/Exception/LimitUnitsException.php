<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Domain\Exception;

final class LimitUnitsException extends \LogicException
{

    public function __construct()
    {
        parent::__construct();
    }
}