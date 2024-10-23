<?php

declare(strict_types=1);

namespace App\Tests\Tools;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Product;
use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\Tests\Resource\Fixture\Orders\OrderFixture;
use App\Tests\Resource\Fixture\Orders\ProductFixture;
use App\Tests\Resource\Fixture\PrintedProductFixture;
use App\Tests\Resource\Fixture\RollFixture;
use App\Tests\Resource\Fixture\UserFixture;
use App\Users\Domain\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

trait FixtureTools
{
    /**
     * Get the DatabaseTools instance.
     */
    public function getDatabaseTools(): AbstractDatabaseTool
    {
        return static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /**
     * Loads a user fixture and returns the user entity.
     *
     * @return User The loaded user entity
     */
    public function loadUserFixture(): User
    {
        $executor = $this->getDatabaseTools()->loadFixtures([UserFixture::class], true);
        /** @var User $user */
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE, User::class);

        return $user;
    }

    /**
     * Loads a roll fixture and returns the roll entity.
     *
     * @return Roll The loaded roll entity
     */
    public function loadRoll(): Roll
    {
        $executor = $this->getDatabaseTools()->loadFixtures([RollFixture::class], true);
        /** @var Roll $roll */
        $roll = $executor->getReferenceRepository()->getReference(RollFixture::REFERENCE, Roll::class);

        return $roll;
    }

    public function loadPrintedProduct(): PrintedProduct
    {
        $executor = $this->getDatabaseTools()->loadFixtures([PrintedProductFixture::class], true);
        /** @var PrintedProduct $printedProduct */
        $printedProduct = $executor->getReferenceRepository()->getReference(PrintedProductFixture::REFERENCE, PrintedProduct::class);

        return $printedProduct;
    }

    public function loadProduct(): Product
    {
        $executor = $this->getDatabaseTools()->loadFixtures([ProductFixture::class], true);
        /** @var Product $product */
        $product = $executor->getReferenceRepository()->getReference(ProductFixture::REFERENCE, Product::class);

        return $product;
    }

    public function loadOrder(): Order
    {
        $executor = $this->getDatabaseTools()->loadFixtures([OrderFixture::class], true);
        /** @var Order $order */
        $order = $executor->getReferenceRepository()->getReference(OrderFixture::REFERENCE, Order::class);

        return $order;
    }
}
