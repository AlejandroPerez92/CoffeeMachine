<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Infrastructure;

use Deliverea\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderEventSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return ['order.line.added' => 'onLineAdded'];
    }

    public function onLineAdded(DomainEvent $event)
    {
        dd($event);
    }
}