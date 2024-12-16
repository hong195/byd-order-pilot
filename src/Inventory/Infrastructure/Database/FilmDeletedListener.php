<?php

namespace App\Inventory\Infrastructure\Database;

use App\Inventory\Domain\Aggregate\AbstractFilm;
use App\Inventory\Domain\Events\FilmWasDeletedEvent;
use App\Inventory\Infrastructure\Event\DomainEventProducer;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[AsDoctrineListener('preRemove')]
class FilmDeletedListener
{
    public function __construct(private DomainEventProducer $eventProducer)
    {
    }

	/**
	 * @throws ExceptionInterface
	 * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
	 */
	public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof AbstractFilm) {
            return;
        }

		$this->eventProducer->produce(new FilmWasDeletedEvent($entity->getId(), $entity->getLength(), $entity->getType()));
    }
}
