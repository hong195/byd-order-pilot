<?php

namespace App\Tests\Functional\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Extra;
use App\Orders\Domain\Repository\ExtraRepositoryInterface;
use App\Orders\Domain\Service\Order\Extra\ExtraMaker;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

class ExtraRepositoryTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private ExtraRepositoryInterface $extraRepository;

    private ExtraMaker $extraMaker;

    /**
     * Set up the test fixture before each test method is called.
     *
     * This method is called before each test method execution to set up the necessary resources and environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->extraRepository = $this->getContainer()->get(ExtraRepositoryInterface::class);
    }

    public function test_can_successfully_save(): void
    {
        /** @var ExtraMaker $extraMaker */
        $extra = new Extra(
            name: $this->getFaker()->word(),
            orderNumber: $this->getFaker()->word(),
            count: $this->getFaker()->numberBetween(1, 10),
        );

        $this->extraRepository->add($extra);

        $this->assertNotNull($extra->getId());
    }
}
