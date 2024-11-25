<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Merge;

use App\ProductionProcess\Domain\Exceptions\InventoryFilmIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\RollMergeException;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Merge\MergeService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Exception\DomainException;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

final class MergeServiceTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;
    private RollRepositoryInterface $rollRepository;
    private MergeService $mergeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
        $this->mergeService = self::getContainer()->get(MergeService::class);
    }

    /**
     * @throws DomainException
     * @throws RollMergeException
     */
    public function test_it_throws_exception_when_there_are_less_than_two_rolls_to_merge(): void
    {
        $rollToMerge1 = $this->loadRoll();

        $this->expectException(RollMergeException::class);

        $this->mergeService->merge([$rollToMerge1->getId()]);
    }

    public function test_it_throws_error_when_at_least_one_roll_does_not_have_printed_products(): void
    {
        $rollToMerge1 = $this->loadRoll();
        $rollToMerge2 = $this->loadRoll();

        $this->expectException(RollMergeException::class);

        $this->mergeService->merge([$rollToMerge1->getId(), $rollToMerge2->getId()]);
    }

    public function test_it_throws_exception_when_there_is_not_enough_film_length(): void
    {
        $availableFilm = $this->getAvailableFilm();

        $rollToMerge1 = $this->createPreparedRoll(filmType: $availableFilm->filmType, length: $availableFilm->length, filmId: $availableFilm->id, productCount: 3);
        $rollToMerge2 = $this->createPreparedRoll(filmType: $availableFilm->filmType, length: $availableFilm->length, filmId: $availableFilm->id, productCount: 3);

        $this->expectException(InventoryFilmIsNotAvailableException::class);

        $this->mergeService->merge([$rollToMerge1->getId(), $rollToMerge2->getId()]);
    }

    public function test_service_take_into_consideration_the_existing_rolls_ready_for_print_check_in(): void
    {
        $availableFilmService = $this->getContainer()->get(AvailableFilmServiceInterface::class);
        $availableFilms = $availableFilmService->getAvailableFilms();

        foreach ($availableFilms as $film) {
            // we occupy all the available films
            $this->createPreparedRoll(filmType: $film->filmType, length: $film->length, filmId: $film->id, productCount: rand(1, 5));
        }

        $firstAvailableFilm = $availableFilms->first();

        $rollToMerge1 = $this->createPreparedRoll(filmType: $firstAvailableFilm->filmType, length: $firstAvailableFilm->length, filmId: $firstAvailableFilm->id, productCount: 3);
        $rollToMerge2 = $this->createPreparedRoll(filmType: $firstAvailableFilm->filmType, length: $firstAvailableFilm->length, filmId: $firstAvailableFilm->id, productCount: 3);

        $this->expectException(InventoryFilmIsNotAvailableException::class);

        $this->mergeService->merge([$rollToMerge1->getId(), $rollToMerge2->getId()]);
    }

    public function test_it_can_merge_several_rolls_into_one_locked_one(): void
    {
        $availableFilm = $this->getAvailableFilm();

        $rollToMerge1 = $this->createPreparedRoll(filmType: $availableFilm->filmType, length: $availableFilm->length / 2, filmId: $availableFilm->id, productCount: 3);
        $rollToMerge1Id = $rollToMerge1->getId();
        $rollToMerge1ProductCount = $rollToMerge1->getPrintedProducts()->count();
        $rollToMerge2 = $this->createPreparedRoll(filmType: $availableFilm->filmType, length: $availableFilm->length / 2, filmId: $availableFilm->id, productCount: 3);
        $rollToMerge2ProductCount = $rollToMerge1->getPrintedProducts()->count();
        $rollToMerge2Id = $rollToMerge2->getId();

        $mergedRollId = $this->mergeService->merge([$rollToMerge1->getId(), $rollToMerge2->getId()]);
        $mergedRoll = $this->rollRepository->findById($mergedRollId);
        $removedRoll1 = $this->rollRepository->findById($rollToMerge1Id);
        $removedRoll2 = $this->rollRepository->findById($rollToMerge2Id);

        $this->assertNotNull($mergedRoll);
        $this->assertTrue($mergedRoll->isLocked());
        $this->assertNull($removedRoll1);
        $this->assertNull($removedRoll2);
        $this->assertCount($rollToMerge1ProductCount + $rollToMerge2ProductCount, $mergedRoll->getPrintedProducts());
        $this->assertNotEmpty($mergedRoll->getPrinter()?->getId());

        foreach ($mergedRoll->getPrintedProducts() as $printedProduct) {
            $this->assertEquals($mergedRoll->getId(), $printedProduct->getRoll()?->getId());
        }
    }

    public function test_it_throws_exception_when_one_of_rolls_are_not_in_correct_process(): void
    {
        $availableFilm = $this->getAvailableFilm();

        $rollToMerge1 = $this->createPreparedRoll(filmType: $availableFilm->filmType, length: $availableFilm->length / 2, filmId: $availableFilm->id, productCount: 3);
        $rollToMerge1->updateProcess(Process::CUTTING_CHECK_IN);
        $this->rollRepository->save($rollToMerge1);

        $rollToMerge2 = $this->createPreparedRoll(filmType: $availableFilm->filmType, length: $availableFilm->length / 2, filmId: $availableFilm->id, productCount: 3);

        $this->expectException(RollMergeException::class);

        $this->mergeService->merge([$rollToMerge1->getId(), $rollToMerge2->getId()]);
    }
}
