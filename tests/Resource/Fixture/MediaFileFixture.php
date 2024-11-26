<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\Shared\Domain\Entity\MediaFile;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Represents a class that loads PrintedProduct fixtures into the database.
 */
final class MediaFileFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'media-file';

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
        $mediaFile = new MediaFile(
            filename: $this->getFaker()->name(),
			source: $this->getFaker()->word(),
			path: $this->getFaker()->url(),
        );

        $manager->persist($mediaFile);
        $manager->flush();

		$this->addReference(self::REFERENCE, $mediaFile);
    }
}
