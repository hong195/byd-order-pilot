<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Inventory;

use App\Inventory\Domain\Aggregate\FilmType;
use App\Inventory\Domain\Factory\FilmFactory;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Represents a class that loads PrintedProduct fixtures into the database.
 */
final class FilmFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'film';

    /**
     * Constructor for the current class. Receives a RollFactory instance as a parameter.
     */
    public function __construct()
    {
    }

    /**
     * Loads a PrintedProduct entity into the database using the given ObjectManager.
     *
     * @param ObjectManager $manager The ObjectManager instance to persist the entity
     */
    public function load(ObjectManager $manager): void
    {
        $film = (new FilmFactory())
            ->make(
                name: $this->getFaker()->name(),
                length: $this->getFaker()->randomNumber(3),
                filmType: FilmType::Film,
                type: $this->getFaker()->randomElement(['chrome', 'echo', 'white'])
            );

        $manager->persist($film);
        $manager->flush();

        $this->addReference(self::REFERENCE, $film);
    }
}
