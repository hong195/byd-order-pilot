<?php

namespace App\Tests\Functional\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\GroupService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Doctrine\Common\Collections\ArrayCollection;

class LaminationGroupServiceTest extends AbstractTestCase
{
    use FixtureTools;
    use FakerTools;

    private GroupService $groupService;

    private PrintedProductRepositoryInterface $printedProductRepository;
    private RollRepositoryInterface $rollRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->groupService = self::getContainer()->get(GroupService::class);
        $this->printedProductRepository = self::getContainer()->get(PrintedProductRepositoryInterface::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    public function test_it_can_group_products(): void
    {
        $lamination1 = $this->getFaker()->word();
        $lamination2 = $this->getFaker()->word();
        $filmType = $this->getFaker()->word();

        $p1 = $this->createPreparedProduct(filmType: $filmType, lamination: $lamination1);
        $p2 = $this->createPreparedProduct(filmType: $filmType, lamination: $lamination1);
        $p3 = $this->createPreparedProduct(filmType: $filmType, lamination: $lamination2);
        $p4 = $this->createPreparedProduct(filmType: $filmType, lamination: $lamination2);
        $p5 = $this->createPreparedProduct(filmType: $filmType, lamination: $lamination2);

        $groupedByLamination = $this->groupService->handle(new ArrayCollection([$p1, $p2, $p3, $p4, $p5]));
        $totalProducts = array_reduce($groupedByLamination, fn ($carry, $item) => $carry + count($item), 0);

        $this->assertCount(count([$lamination1, $lamination2]), array_values($groupedByLamination));
        $this->assertCount($totalProducts, [$p1, $p2, $p3, $p4, $p5]);
    }
}
