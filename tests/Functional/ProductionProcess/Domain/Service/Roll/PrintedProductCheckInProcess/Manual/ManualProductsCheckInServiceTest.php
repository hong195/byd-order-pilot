<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Exceptions\InventoryFilmIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual\ManualProductsCheckInService;
use App\Shared\Domain\Exception\DomainException;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;

class ManualProductsCheckInServiceTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private PrintedProductRepositoryInterface $printedProductRepository;

    private ManualProductsCheckInService $manualProductsCheckInService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->printedProductRepository = $this->getContainer()->get(PrintedProductRepositoryInterface::class);
        $this->manualProductsCheckInService = $this->getContainer()->get(ManualProductsCheckInService::class);
    }

    /**
     * @throws DomainException
     */
    public function test_it_throws_exception_when_there_is_not_enough_film_to_print_printed_products(): void
    {
        $firstAvailableFilm = $this->getAvailableFilm();

        $pp1 = $this->createPreparedProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length * 2);
        $pp2 = $this->createPreparedProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length * 2);

        $this->expectException(InventoryFilmIsNotAvailableException::class);

        $this->manualProductsCheckInService->arrange([$pp1->getId(), $pp2->getId()]);
    }

    /**
     * @throws ManualArrangeException
     * @throws DomainException
     */
    public function test_it_successfully_manually_arrange_printed_products_on_locked_roll(): void
    {
        $firstAvailableFilm = $this->getAvailableFilm();

        $pp1 = $this->createPreparedProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length / 2);
        $pp2 = $this->createPreparedProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length / 2);

        $this->manualProductsCheckInService->arrange([$pp1->getId(), $pp2->getId()]);

        $manuallyArrangedProduct1 = $this->printedProductRepository->findById($pp1->getId());
        $manuallyArrangedProduct2 = $this->printedProductRepository->findById($pp1->getId());

        $this->assertNotEmpty($manuallyArrangedProduct2->getRoll());
        $this->assertNotEmpty($manuallyArrangedProduct1->getRoll());
        $this->assertTrue($manuallyArrangedProduct1->getRoll()->getId() === $manuallyArrangedProduct2->getRoll()->getId());
    }

    /**
     * Test shows that the service takes into consideration the existing rolls ready for printing,
     * for example, when there are already have rolls with the same film  and enough length to print the products
     * it will arrange them manually.
     *
     * @throws DomainException
     */
    public function test_service_take_into_consideration_the_existing_rolls_ready_for_print_check_in(): void
    {
        $firstAvailableFilm = $this->getAvailableFilm();
        // the length will be divided into 3 parts, to make sure service will arrange the products
        $availableFilmLength = $firstAvailableFilm->length;

        $this->createPreparedRoll($firstAvailableFilm->filmType, $availableFilmLength / 3, $firstAvailableFilm->id);

        $pp1 = $this->createPreparedProduct($firstAvailableFilm->filmType, $availableFilmLength / 3);
        $pp2 = $this->createPreparedProduct($firstAvailableFilm->filmType, $availableFilmLength / 3);

        $this->manualProductsCheckInService->arrange([$pp1->getId(), $pp2->getId()]);

        $manuallyArrangedProduct1 = $this->printedProductRepository->findById($pp1->getId());
        $manuallyArrangedProduct2 = $this->printedProductRepository->findById($pp1->getId());

        $this->assertTrue($manuallyArrangedProduct1->getRoll()->getId() === $manuallyArrangedProduct2->getRoll()->getId());
    }

    /**
     * Test will throw exception when there is a ready for print check in roll exceed the available film length.
     *
     * @throws DomainException
     */
    public function test_it_throws_exception_when_existing_roll_ready_for_print_check_in_takes_to_much_length(): void
    {
        $availableFilms = $this->getContainer()->get(AvailableFilmServiceInterface::class)->getAvailableFilms('chrome');
        $firstAvailableFilm = $availableFilms->first();

        foreach ($availableFilms as $availableFilm) {
            $this->createPreparedRoll($availableFilm->filmType, $availableFilm->length, $availableFilm->id);
        }

        $pp1 = $this->createPreparedProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length / 3);
        $pp2 = $this->createPreparedProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length / 3);

        $this->expectException(InventoryFilmIsNotAvailableException::class);

        $this->manualProductsCheckInService->arrange([$pp1->getId(), $pp2->getId()]);

        $notArrangedProduct1 = $this->printedProductRepository->findById($pp1->getId());
        $notArrangedProduct2 = $this->printedProductRepository->findById($pp2->getId());

        $this->assertNull($notArrangedProduct1->getRoll());
        $this->assertNull($notArrangedProduct2->getRoll());
    }
}
