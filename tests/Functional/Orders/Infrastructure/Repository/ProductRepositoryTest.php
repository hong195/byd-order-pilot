<?php

namespace App\Tests\Functional\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\Factory\ProductFactory;
use App\Orders\Domain\Repository\ProductFilter;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

class ProductRepositoryTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private ProductRepositoryInterface $productRepository;

    private Product $product1;
    private Product $product2;
    private Order $order;

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
        $this->make_products();

        $product1 = $this->productRepository->findById($this->product1->getId());
        $product2 = $this->productRepository->findById($this->product2->getId());

        $this->assertEquals($product1->getId(), $this->product1->getId());
        $this->assertEquals($product2->getId(), $this->product2->getId());
    }

    public function test_can_find_by_filter(): void
    {
        $this->make_products();

        $products = $this->productRepository->findByFilter(
            new ProductFilter(orderId: $this->order->getId())
        )->toArray();

        $products2 = $this->productRepository->findByFilter(
            new ProductFilter(productIds: [$this->product1->getId(), $this->product2->getId()])
        )->toArray();

        $this->assertContains($this->product1->getId(), array_map(fn (Product $product) => $product->getId(), $products));
        $this->assertContains($this->product2->getId(), array_map(fn (Product $product) => $product->getId(), $products));

        $this->assertContains($this->product1->getId(), array_map(fn (Product $product) => $product->getId(), $products2));
        $this->assertContains($this->product2->getId(), array_map(fn (Product $product) => $product->getId(), $products2));
    }

    private function make_products(): void
    {
        $this->order = $this->loadOrder();

        $this->product1 = (new ProductFactory())
            ->make(
                filmType: $this->getFaker()->word(),
                length: $this->getFaker()->randomFloat(2, 1, 100),
                laminationType: $this->getFaker()->word(),
            );

        $this->product2 = (new ProductFactory())
            ->make(
                filmType: $this->getFaker()->word(),
                length: $this->getFaker()->randomFloat(2, 1, 100),
                laminationType: $this->getFaker()->word(),
            );

        $this->productRepository->save($this->product1);
        $this->productRepository->save($this->product2);

        $this->order->addProduct($this->product1);
        $this->order->addProduct($this->product2);

        $this->entityManager->flush();
    }
}
