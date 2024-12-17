<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Database;

use App\Inventory\Domain\Aggregate\AggregateRoot;
use App\Inventory\Infrastructure\Event\DomainEventProducer;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[AsDoctrineListener(event: Events::onFlush)]
final readonly class PublishAggregateEventsOnFlushListener
{
    public function __construct(private DomainEventProducer $eventProducer)
    {
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $unitOfWork = $eventArgs->getObjectManager()->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            $this->publishDomainEvent($entity);
        }

        foreach ($unitOfWork->getScheduledEntityUpdates() as $entity) {
            $this->publishDomainEvent($entity);
        }

        foreach ($unitOfWork->getScheduledEntityDeletions() as $entity) {
            $this->publishDomainEvent($entity);
        }

        foreach ($unitOfWork->getScheduledCollectionDeletions() as $collection) {
            foreach ($collection as $entity) {
                $this->publishDomainEvent($entity);
            }
        }

        foreach ($unitOfWork->getScheduledCollectionUpdates() as $collection) {
            foreach ($collection as $entity) {
                $this->publishDomainEvent($entity);
            }
        }
    }

    /**
     * @throws ExceptionInterface
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    private function publishDomainEvent(object $entity): void
    {
        if ($entity instanceof AggregateRoot && !$entity->eventsEmpty()) {
            $this->eventProducer->produce(...$entity->pullEvents());
        }
    }
}
