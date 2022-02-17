<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Shared\Domain\Wallet;

use Deliverea\CoffeeMachine\Shared\Domain\Uuid\Uuid;

final class WalletId
{
    private Uuid $id;

    public function __construct(string $id)
    {
        $this->id = new Uuid($id);
    }

    public static function Create(): WalletId
    {
        return new WalletId((string)Uuid::Create());
    }

    public function value(): string
    {
        return (string)$this->id;
    }

    public function equals(WalletId $toCompare): bool
    {
        return $this->id->value() === $toCompare->value();
    }
}