<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Orders;

use App\Orders\Domain\Factory\ProductFactory;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Represents a class that loads PrintedProduct fixtures into the database.
 */
final class ProductFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'order_product';

    /**
     * Constructor for the current class. Receives a RollFactory instance as a parameter.
     */
    public function __construct(private ProductFactory $factory)
    {
    }

    /**
     * Loads a PrintedProduct entity into the database using the given ObjectManager.
     *
     * @param ObjectManager $manager The ObjectManager instance to persist the entity
     */
    public function load(ObjectManager $manager): void
    {
        $product = $this->factory->make(
            filmType: $this->getFaker()->word(),
            length: $this->getFaker()->numberBetween(1, 100),
            laminationType: $this->getFaker()->optional()->word()
        );

        $manager->persist($product);
        $manager->flush();

        $this->addReference(self::REFERENCE, $product);
    }
}
