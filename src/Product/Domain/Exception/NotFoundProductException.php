<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Product\Domain\Exception;

final class NotFoundProductException extends \RuntimeException
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function FromName(string $name): self
    {
        return new self(sprintf("Product with name %s not found", $name));
    }
}