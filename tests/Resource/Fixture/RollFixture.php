<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture;

use App\ProductionProcess\Domain\Factory\RollFactory;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Represents a class that loads PrintedProduct fixtures into the database.
 */
final class RollFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'roll';

    /**
     * Constructor for the current class. Receives a RollFactory instance as a parameter.
     *
     * @param RollFactory $factory The RollFactory instance to be used within the current class
     */
    public function __construct(private readonly RollFactory $factory)
    {
    }

    /**
     * Loads a PrintedProduct entity into the database using the given ObjectManager.
     *
     * @param ObjectManager $manager The ObjectManager instance to persist the entity
     */
    public function load(ObjectManager $manager): void
    {
        $roll = $this->factory->create(
            name: $this->getFaker()->name(),
            process: Process::CUTTING_CHECK_IN,
        );

        $manager->persist($roll);
        $manager->flush();

        $this->addReference(self::REFERENCE, $roll);
    }
}
