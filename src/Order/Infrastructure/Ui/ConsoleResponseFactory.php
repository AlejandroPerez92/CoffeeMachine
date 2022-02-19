<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Infrastructure\Ui;

final class ConsoleResponseFactory
{
    public static function Create(ConsoleQueryObject $object): string
    {
        $response = '';
        $response = 'You have ordered a ' . $object->product();

        if ($object->isExtraHot()) {
            $response .= ' extra hot';
        }

        if ($object->sugars() > 0) {
            $response .= ' with ' . $object->sugars() . ' sugars (stick included)';
        }

        $response .= '';

        return $response;
    }
}