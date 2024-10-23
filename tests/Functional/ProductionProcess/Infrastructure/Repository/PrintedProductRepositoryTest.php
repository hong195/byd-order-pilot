<?php

namespace App\Tests\Functional\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Factory\PrintedProductFactory;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

class PrintedProductRepositoryTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;
    private PrintedProductRepositoryInterface $printedProductRepository;

    /**
     * Set up the test fixture before each test method is called.
     *
     * This method is called before each test method execution to set up the necessary resources and environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->printedProductRepository = $this->getContainer()->get(PrintedProductRepositoryInterface::class);
    }

    /**
     * Test the add method in the repository.
     *
     * This method tests the add functionality of the repository by creating two Error objects using ErrorFactory,
     * adding them to the repository, and then asserting that the added errors can be retrieved by the responsible employee ID.
     */
    public function test_can_save_successfully(): void
    {
        $printedProduct = $this->get_test_printed_product();

        $this->printedProductRepository->save($printedProduct);

        $addedPrintedProduct = $this->printedProductRepository->findById($printedProduct->getId());

        $this->assertSame($addedPrintedProduct, $printedProduct);
    }

    public function test_can_find_by_multiple_related_product_ids(): void
    {
        $printedProduct1 = $this->get_test_printed_product();
        $printedProduct2 = $this->get_test_printed_product();

        $this->printedProductRepository->save($printedProduct1);
        $this->printedProductRepository->save($printedProduct2);

        $ids = [$printedProduct1->relatedProductId, $printedProduct2->relatedProductId];

        $resultByIds = $this->printedProductRepository->findByRelatedProductIds($ids);

        $this->assertContains($printedProduct1, $resultByIds);
        $this->assertContains($printedProduct2, $resultByIds);
    }

    private function get_test_printed_product(): PrintedProduct
    {
        return (new PrintedProductFactory())->make(
            relatedProductId: $this->getFaker()->numberBetween(1, 100),
            orderNumber: (string) $this->getFaker()->numberBetween(1, 100),
            filmType: $this->getFaker()->word(),
            length: $this->getFaker()->numberBetween(1, 100),
        );
    }
}
