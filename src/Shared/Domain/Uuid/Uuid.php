<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Domain\Uuid;

use Ramsey\Uuid\Uuid as Ramsey;

final class Uuid
{
    private string $id;

    public function __construct(string $id)
    {
        if (!Ramsey::isValid($id)) {
            throw new NotValidUuidException($id);
        }

        $this->id = $id;
    }

    static function Create(): Uuid
    {
        $uuid = Ramsey::uuid4();
        return new Uuid($uuid->toString());
    }

    public function value(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals(Uuid $uuid): bool
    {
        return $this->id === $uuid->value();
    }
}