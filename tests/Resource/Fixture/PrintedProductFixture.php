<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Represents a class that loads PrintedProduct fixtures into the database.
 */
final class PrintedProductFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'printed_product';

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
        $printedProduct = new PrintedProduct(
            relatedProductId: $this->getFaker()->randomDigit(),
            orderNumber: $this->getFaker()->word(),
            filmType: $this->getFaker()->word(),
            length: $this->getFaker()->randomDigit()
        );

        $manager->persist($printedProduct);
        $manager->flush();

		$this->addReference(self::REFERENCE, $printedProduct);
    }
}
