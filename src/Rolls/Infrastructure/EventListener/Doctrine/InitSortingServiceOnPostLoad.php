<?php

declare(strict_types=1);

namespace App\Rolls\Infrastructure\EventListener\Doctrine;

use App\Rolls\Domain\Aggregate\OrderStack\OrderStack;
use App\Rolls\Domain\Service\SortOrdersServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

#[AsDoctrineListener(event: Events::postLoad)]
final readonly class InitSortingServiceOnPostLoad
{
    public function __construct(private ContainerBagInterface $container, private SortOrdersServiceInterface $sortOrdersService)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
        ];
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!($entity instanceof OrderStack)) {
            return;
        }

        $reflect = new \ReflectionClass($entity);

        foreach ($reflect->getProperties() as $property) {
            $type = $property->getType();

            if (is_null($type) || $property->isInitialized($entity)) {
                continue;
            }

            if (SortOrdersServiceInterface::class === $type->getName()) {
                $property->setValue($entity, $this->sortOrdersService);
            }
        }
    }
}
