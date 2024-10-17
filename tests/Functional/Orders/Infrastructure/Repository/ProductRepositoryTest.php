<?php

namespace App\Tests\Functional\Orders\Infrastructure\Repository;

use App\Orders\Domain\Factory\ProductFactory;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

class ProductRepositoryTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private ProductRepositoryInterface $productRepository;

    /**
     * Set up the test fixture before each test method is called.
     *
     * This method is called before each test method execution to set up the necessary resources and environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository = $this->getContainer()->get(ProductRepositoryInterface::class);
    }

    public function test_can_successfully_save(): void
    {
        $productToSave = (new ProductFactory())
                ->make(
                    filmType: $this->getFaker()->word(),
                    length: $this->getFaker()->randomFloat(2, 1, 100),
                    laminationType: $this->getFaker()->word(),
                );

        $this->productRepository->save($productToSave);

        $product = $this->productRepository->findById($productToSave->getId());

        $this->assertEquals($productToSave->getId(), $product->getId());
    }
}
