<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class AbstractTestCase extends KernelTestCase
{
    protected ?EntityManagerInterface $entityManager = null;

    /**
     * Set up the test environment for the Symfony application.
     * Boots the kernel and initializes the database.
     *
     * @throws \LogicException if execution is not in the 'test' environment
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        if ('test' !== $kernel->getEnvironment()) {
            throw new \LogicException('Execution only in Test environment possible!');
        }

        $this->initDatabase($kernel);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Initializes the database schema based on the metadata using the Doctrine ORM.
     *
     * @param KernelInterface $kernel The Symfony Kernel
     */
    private function initDatabase(KernelInterface $kernel): void
    {
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metaData = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metaData);
    }

    /**
     * Tear down the test environment for the Symfony application.
     * Closes the entity manager and sets it to null.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
