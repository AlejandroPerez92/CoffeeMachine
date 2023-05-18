<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\App\DependencyInjection\CompilerPass;

use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\EventSubscriberInterface;
use AlexPerez\CoffeeMachine\Shared\Infrastructure\Eventbus\EventBusWrapper;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class SubscribersCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        /** @var EventBusWrapper $eventBus */
        $eventBusDefinition = $container->getDefinition('alex-perez.event_bus');
        foreach ($container->findTaggedServiceIds('domain.event_subscriber') as $id => $tags) {
            $eventBusDefinition->addMethodCall('addSubscriber', [new Reference($id)]);
        }
    }
}