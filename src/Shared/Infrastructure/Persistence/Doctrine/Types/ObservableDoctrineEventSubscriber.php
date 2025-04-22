<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\Bundle\MongoDBBundle\Attribute\AsDocumentListener;
use Doctrine\Common\EventArgs;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use OpenTelemetry\API\Trace\SpanInterface;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\Context\Context;

#[AsDocumentListener('prePersist', connection: 'default', priority: 500)]
#[AsDocumentListener('postPersist', connection: 'default', priority: 500)]
#[AsDocumentListener('preUpdate', connection: 'default', priority: 500)]
#[AsDocumentListener('postUpdate', connection: 'default', priority: 500)]
#[AsDocumentListener('preRemove', connection: 'default', priority: 500)]
#[AsDocumentListener('postRemove', connection: 'default', priority: 500)]
#[AsDocumentListener('preLoad', connection: 'default', priority: 500)]
#[AsDocumentListener('postLoad', connection: 'default', priority: 500)]
final class ObservableDoctrineEventSubscriber
{
    /**
     * @var array<string, SpanInterface>
     */
    private array $spans;

    public function __construct(private TracerInterface $tracer)
    {
        $this->spans = [];
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void
    {
        $this->startSpan('prePersist', $eventArgs);
    }

    public function postPersist(LifecycleEventArgs $eventArgs): void
    {
        $this->stopSpan($this->buildSpanName('prePersist'));
    }

    public function preUpdate($eventArgs): void
    {
        $this->startSpan('preUpdate', $eventArgs);
    }

    public function postUpdate($eventArgs): void
    {
        $this->stopSpan($this->buildSpanName('preUpdate'));
    }

    public function preRemove($eventArgs): void
    {
        $this->startSpan('preRemove', $eventArgs);
    }

    public function postRemove($eventArgs): void
    {
        $this->stopSpan($this->buildSpanName('preRemove'));
    }

    public function preFetch($eventArgs): void
    {
        $this->startSpan('preFetch', $eventArgs);
    }

    public function postFetch($eventArgs): void
    {
        $this->stopSpan($this->buildSpanName('preFetch'));
    }

    private function startSpan(string $eventName, EventArgs $eventArgs): void
    {
        $spanName = $this->buildSpanName($eventName);
        $scope = Context::storage()->scope();
        $span = $this->tracer
            ->spanBuilder($spanName)
            ->setSpanKind(SpanKind::KIND_CLIENT)
            ->setParent($scope?->context());

        if ($eventArgs instanceof LifecycleEventArgs) {
            $documentClass = get_class($eventArgs->getDocument());
            $span->setAttribute('doctrine.odm.document', $documentClass);
        }

        $span->setAttribute('doctrine.odm.operation', $eventName);
        $this->spans[$spanName] = $span->startSpan();
    }

    private function stopSpan(string $spanName): void
    {
        if (!key_exists($spanName, $this->spans)) {
            return;
        }

        $span = $this->spans[$spanName];
        $span->end();
    }

    private function buildSpanName(string $eventName): string
    {
        return 'doctrine.odm.' . strtolower($eventName);
    }
}