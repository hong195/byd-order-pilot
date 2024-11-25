<?php

declare(strict_types=1);

namespace App\Tests\Tools;

use App\Inventory\Domain\Aggregate\RollFilm;
use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Product;
use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\DTO\FilmData;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Tests\Resource\Fixture\Inventory\FilmFixture;
use App\Tests\Resource\Fixture\Orders\OrderFixture;
use App\Tests\Resource\Fixture\Orders\ProductFixture;
use App\Tests\Resource\Fixture\PrintedProductFixture;
use App\Tests\Resource\Fixture\RollFixture;
use App\Tests\Resource\Fixture\UserFixture;
use App\Users\Domain\Entity\User;
use Doctrine\Common\Collections\Collection;
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

    public function loadInventoryFilm(): RollFilm
    {
        return $this->getDatabaseTools()->loadFixtures([FilmFixture::class], true)
            ->getReferenceRepository()
            ->getReference(FilmFixture::REFERENCE, RollFilm::class);
    }

    public function createPreparedProduct(?string $filmType, float $length = 0, ?string $lamination = null): PrintedProduct
    {
        $printedProduct = new PrintedProduct(
            relatedProductId: $this->getFaker()->randomDigit(),
            orderNumber: $this->getFaker()->word(),
            filmType: $filmType,
            length: $length
        );

        if ($lamination) {
            $printedProduct->setLaminationType($lamination);
        }

        $this->entityManager->persist($printedProduct);
        $this->entityManager->flush();

        return $printedProduct;
    }

    public function createPreparedRoll(string $filmType, float $length = 0, ?int $filmId = null, ?string $lamination = null, ?int $productCount = 1): Roll
    {
        $roll = $this->loadRoll();
        $roll->updateProcess(Process::ORDER_CHECK_IN);

        for ($i = 1; $i <= $productCount; ++$i) {
            $product = $this->createPreparedProduct($filmType, $length / $productCount, $lamination);
            $roll->addPrintedProduct($product);
        }

        if ($filmId) {
            $roll->setFilmId($filmId);
        }

        $this->entityManager->persist($roll);
        $this->entityManager->flush();

        return $roll;
    }

    public function getAvailableFilm(): FilmData
    {
        /** @var AvailableFilmServiceInterface $availableFilms */
        $availableFilmsService = $this->getContainer()->get(AvailableFilmServiceInterface::class);
        /** @var Collection<FilmData> $availableFilms */
        $availableFilms = $availableFilmsService->getAvailableFilms('chrome');

        /* @var FilmData $firstAvailable */
        return $availableFilms->first();
    }
}
