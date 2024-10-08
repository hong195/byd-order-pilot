<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture;

use App\Tests\Tools\FakerTools;
use App\Users\Domain\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Represents a class that loads PrintedProduct fixtures into the database.
 */
final class UserFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'user';

    /**
     * Constructor for the Symfony application.
     *
     * @param UserFactory $factory the UserFactory instance used for creating users
     */
    public function __construct(private readonly UserFactory $factory)
    {
    }

    /**
     * Loads a PrintedProduct entity into the database using the given ObjectManager.
     *
     * @param ObjectManager $manager The ObjectManager instance to persist the entity
     */
    public function load(ObjectManager $manager): void
    {
        $name = $this->getFaker()->name();
        $email = $this->getFaker()->email();
        $password = $this->getFaker()->password();

        $user = $this->factory->create($name, $email, $password);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::REFERENCE, $user);
    }
}
