<?php

namespace App\Tests\Functional\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Service\PrintedProduct\SortService;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class SortServiceTest extends TestCase
{
    private SortService $sortService;

    public function setUp(): void
    {
        $this->sortService = new SortService();
    }

    /**
     * Test if getSorted method returns sorted PrintedProducts based on provided properties criteria.
     */
    public function test_get_sorted(): void
    {
        $filmType = 'film1';
        $product1 = new PrintedProduct(1, 'order1', $filmType, 5);
        $product1->changeSortOrder(1);

        $product2 = new PrintedProduct(2, 'order2', $filmType, 7);
        $product2->changeSortOrder(2);

        $products = new ArrayCollection([$product1, $product2]);

        $sortedProducts = $this->sortService->getSorted($products);

        $this->assertInstanceOf(ArrayCollection::class, $sortedProducts);
        $this->assertSame($product1, $sortedProducts->first());
        $this->assertSame($product2, $sortedProducts->last());
    }
}
