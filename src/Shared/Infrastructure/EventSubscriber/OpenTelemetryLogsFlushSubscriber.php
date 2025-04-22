<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\EventSubscriber;

use OpenTelemetry\SDK\Logs\LoggerProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class OpenTelemetryLogsFlushSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerProviderInterface $loggerProvider,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => 'onKernelTerminate',
        ];
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $this->loggerProvider->forceFlush();
    }
}