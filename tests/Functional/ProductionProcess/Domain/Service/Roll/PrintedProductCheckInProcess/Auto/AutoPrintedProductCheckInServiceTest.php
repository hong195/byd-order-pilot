<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto;

use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\AutoCheckInPrintedProductsService;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class AutoPrintedProductCheckInServiceTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;
    private AutoCheckInPrintedProductsService $autoPrintedProductCheckInService;

    private PrintedProductRepositoryInterface $printedProductRepository;
    private RollRepositoryInterface $rollRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->autoPrintedProductCheckInService = self::getContainer()->get(AutoCheckInPrintedProductsService::class);
        $this->printedProductRepository = self::getContainer()->get(PrintedProductRepositoryInterface::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
    }

    public function test_it_can_auto_check_in_printed_products(): void
    {
        $product1 = $this->createPreparedProduct(filmType: 'chrome', length: 2);
        $product2 = $this->createPreparedProduct(filmType: 'chrome', length: 2, lamination: 'glossy');

        $product3 = $this->createPreparedProduct(filmType: 'white', length: 2);
        $product4 = $this->createPreparedProduct(filmType: 'white', length: 2);

        $product5 = $this->createPreparedProduct(filmType: 'neon', length: 2);
        $product6 = $this->createPreparedProduct(filmType: 'neon', length: 2);

        $printedProducts = [$product1->getId(), $product2->getId(), $product3->getId(), $product4->getId(), $product5->getId(), $product6->getId()];

        $unassignedProductIds = $this->autoPrintedProductCheckInService->arrange($printedProducts);

        $this->assertEmpty($unassignedProductIds);
        $this->assertTrue($product1->getRoll()->getId() === $product2->getRoll()->getId());
        $this->assertTrue($product3->getRoll()->getId() === $product4->getRoll()->getId());
        $this->assertTrue($product5->getRoll()->getId() === $product6->getRoll()->getId());
    }

    public function test_it_returns_unassigned_ids_if_it_there_is_not_enough_film(): void
    {
        $availableFilm = $this->getAvailableFilm();

        $assgnableIds = [];
        $randomElNumber = $this->getFaker()->randomDigit();

        for ($i = 0; $i < $randomElNumber; ++$i) {
            $assgnableIds[] = $this->createPreparedProduct(filmType: $availableFilm->filmType, length: $availableFilm->length / $randomElNumber)->getId();
        }

        $unassignableProductId = $this->createPreparedProduct(filmType: $availableFilm->filmType, length: 100_000_000_000)->getId();

        $unassignedProductIds = $this->autoPrintedProductCheckInService->arrange(array_merge($assgnableIds, [$unassignableProductId]));

        $this->assertContains($unassignableProductId, $unassignedProductIds);
    }

    public function test_it_removes_old_rolls_and_creates_new_ones(): void
    {
        $availableFilm = $this->getAvailableFilm();

        $assgnableProduct = $this->createPreparedProduct(filmType: $availableFilm->filmType, length: $availableFilm->length / 2);

        $this->autoPrintedProductCheckInService->arrange([$assgnableProduct->getId()]);
        $assignedProduct = $this->printedProductRepository->findById($assgnableProduct->getId());
        $assigned1ProductRollId = $assignedProduct->getRoll()->getId();

        $this->autoPrintedProductCheckInService->arrange([$assgnableProduct->getId()]);
        $assignedProduct = $this->printedProductRepository->findById($assgnableProduct->getId());
        $assigned2ProductRollId = $assignedProduct->getRoll()->getId();

        $removedRoll = $this->rollRepository->findById($assigned1ProductRollId);
        $this->assertFalse($assigned1ProductRollId === $assigned2ProductRollId);
        $this->assertNull($removedRoll);
    }

    public function test_it_does_not_modify_or_remove_locked_roll(): void
    {
        $availableFilm = $this->getAvailableFilm();
        $lockedRoll = $this->createPreparedRoll(filmType: $availableFilm->filmType, length: $availableFilm->length / 3);
        $lockedRoll->lock();
        $this->entityManager->persist($lockedRoll);
        $this->entityManager->flush();

        $assgnableProduct = $this->createPreparedProduct(filmType: $availableFilm->filmType, length: $availableFilm->length / 3);
        $assgnableProduct2 = $this->createPreparedProduct(filmType: $availableFilm->filmType, length: $availableFilm->length / 3);
        $this->autoPrintedProductCheckInService->arrange([$assgnableProduct->getId(), $assgnableProduct2->getId()]);

        $this->assertNotEmpty($lockedRoll->getId());
        $this->assertTrue($lockedRoll->isLocked());
        $this->assertNotEmpty($lockedRoll->getPrintedProducts());
    }

    public function test_it_takes_into_consideration_previously_created_rolls(): void
    {
        $availableFilms = $this->getContainer()->get(AvailableFilmServiceInterface::class)->getAvailableFilms();
        $this->occupyAvailableFilms(shouldBeLocked: false);

        $randomAvailableFilm = $availableFilms[rand(0, count($availableFilms) - 1)];

        $unassgnableProduct = $this->createPreparedProduct(filmType: $randomAvailableFilm->filmType, length: $randomAvailableFilm->length);

        $unassignedProductIds = $this->autoPrintedProductCheckInService->arrange([$unassgnableProduct->getId()]);

        $this->assertNotEmpty($unassignedProductIds);
        $this->assertContains($unassgnableProduct->getId(), $unassignedProductIds);
    }

    public function test_it_takes_into_consideration_previously_created_locked_rolls(): void
    {
        $availableFilms = $this->getContainer()->get(AvailableFilmServiceInterface::class)->getAvailableFilms();
        $this->occupyAvailableFilms(shouldBeLocked: true);

        $randomAvailableFilm = $availableFilms[rand(0, count($availableFilms) - 1)];
        $unassgnableProduct = $this->createPreparedProduct(filmType: $randomAvailableFilm->filmType, length: $randomAvailableFilm->length);

        $unassignedProductIds = $this->autoPrintedProductCheckInService->arrange([$unassgnableProduct->getId()]);

        $this->assertNotEmpty($unassignedProductIds);
        $this->assertContains($unassgnableProduct->getId(), $unassignedProductIds);
    }

    public function test_it_can_not_place_products_with_the_same_order_number_if_there_is_no_suitable_length(): void
    {
        $availableFilms = $this->getContainer()->get(AvailableFilmServiceInterface::class)->getAvailableFilms();
        $this->occupyAvailableFilms(shouldBeLocked: false);

        $orderNumber = $this->getFaker()->word();

        $randomAvailableFilm = $availableFilms[rand(0, count($availableFilms) - 1)];
        $unassgnableProduct = $this->createPreparedProduct(filmType: $randomAvailableFilm->filmType, length: $randomAvailableFilm->length, orderNumber: $orderNumber);
        $unassgnableProduct2 = $this->createPreparedProduct(filmType: $randomAvailableFilm->filmType, length: $randomAvailableFilm->length, orderNumber: $orderNumber);
        $unassgnableProduct3 = $this->createPreparedProduct(filmType: $randomAvailableFilm->filmType, length: $randomAvailableFilm->length, orderNumber: $orderNumber);

        $unassgnableProductIds = [$unassgnableProduct->getId(), $unassgnableProduct2->getId(), $unassgnableProduct3->getId()];
        $unassignedProductIds = $this->autoPrintedProductCheckInService->arrange($unassgnableProductIds);

        $this->assertNotEmpty($unassignedProductIds);

        foreach ($unassgnableProductIds as $unassgnableProductId) {
            $product = $this->printedProductRepository->findById($unassgnableProductId);
            $this->assertNull($product->getRoll());
            $this->assertContains($unassgnableProductId, $unassignedProductIds);
        }
    }

    private function occupyAvailableFilms(bool $shouldBeLocked): void
    {
        $availableFilms = $this->getContainer()->get(AvailableFilmServiceInterface::class)->getAvailableFilms();

        foreach ($availableFilms as $availableFilm) {
            // we occupy all available films
            $roll = $this->createPreparedRoll(
                filmType: $availableFilm->filmType,
                length: $availableFilm->length,
                filmId: $availableFilm->id,
                productCount: rand(1, 5)
            );

            if ($shouldBeLocked) {
                $roll->lock();
            }

            $this->entityManager->persist($roll);
        }

        $this->entityManager->flush();
    }
}
