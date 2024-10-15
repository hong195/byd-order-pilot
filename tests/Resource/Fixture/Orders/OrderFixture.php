<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Orders;

use App\Orders\Domain\Aggregate\Customer;
use App\Orders\Domain\Factory\OrderFactory;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Represents a class that loads PrintedProduct fixtures into the database.
 */
final class OrderFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'order_order';

    /**
     * Constructor for the current class. Receives a RollFactory instance as a parameter.
     */
    public function __construct(private OrderFactory $factory)
    {
    }

    /**
     * Loads a PrintedProduct entity into the database using the given ObjectManager.
     *
     * @param ObjectManager $manager The ObjectManager instance to persist the entity
     */
    public function load(ObjectManager $manager): void
    {
        $order = $this->factory->make(
            customer: new Customer(
                name: $this->getFaker()->name,
                notes: $this->getFaker()->optional()->text
            ),
            shippingAddress: $this->getFaker()->address,
            orderNumber: $this->getFaker()->word
        );

        $manager->persist($order);
        $manager->flush();

        $this->addReference(self::REFERENCE, $order);
    }
}
