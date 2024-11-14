<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\DTO\FilmData;
use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual\ManualProductsArrangeService;
use App\Shared\Domain\Exception\DomainException;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Doctrine\Common\Collections\Collection;

class ManualProductsArrangeServiceTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private RollRepositoryInterface $rollRepository;
    private PrintedProductRepositoryInterface $printedProductRepository;

    private ManualProductsArrangeService $manualProductsArrangeService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rollRepository = $this->getContainer()->get(RollRepositoryInterface::class);
        $this->printedProductRepository = $this->getContainer()->get(PrintedProductRepositoryInterface::class);
        $this->manualProductsArrangeService = $this->getContainer()->get(ManualProductsArrangeService::class);
    }

    /**
     * @throws ManualArrangeException
     * @throws DomainException
     */
    public function test_it_throws_exception_when_printed_products_have_different_film_type(): void
    {
        $pp1 = $this->prepareProduct($this->getFaker()->word());
        $pp2 = $this->prepareProduct($this->getFaker()->word());

        $this->expectException(ManualArrangeException::class);

        $this->manualProductsArrangeService->arrange([$pp1->getId(), $pp2->getId()]);
    }

    /**
     * @throws DomainException
     */
    public function test_it_throws_exception_when_printed_products_handled_by_different_printers(): void
    {
        // see prod_process_printer_condition for available options
        $pp1 = $this->prepareProduct('chrome', 0.5);
        $pp2 = $this->prepareProduct('white', 0.5, 'holo_flakes');

        $this->expectException(ManualArrangeException::class);

        $this->manualProductsArrangeService->arrange([$pp1->getId(), $pp2->getId()]);
    }

    /**
     * @throws DomainException
     */
    public function test_it_throws_exception_when_there_is_not_enough_film_to_print_printed_products(): void
    {
        $firstAvailableFilm = $this->getAvailableFilm();

        $pp1 = $this->prepareProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length * 2);
        $pp2 = $this->prepareProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length * 2);

        $this->expectException(ManualArrangeException::class);

        $this->manualProductsArrangeService->arrange([$pp1->getId(), $pp2->getId()]);
    }

    /**
     * @throws ManualArrangeException
     * @throws DomainException
     */
    public function test_it_successfully_manually_arrange_printed_products_on_locked_roll(): void
    {
        $firstAvailableFilm = $this->getAvailableFilm();

        $pp1 = $this->prepareProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length / 2);
        $pp2 = $this->prepareProduct($firstAvailableFilm->filmType, $firstAvailableFilm->length / 2);

        $this->manualProductsArrangeService->arrange([$pp1->getId(), $pp2->getId()]);

        $manuallyArrangedProduct1 = $this->printedProductRepository->findById($pp1->getId());
        $manuallyArrangedProduct2 = $this->printedProductRepository->findById($pp1->getId());

        $this->assertNotEmpty($manuallyArrangedProduct2->getRoll()->getId());
        $this->assertNotEmpty($manuallyArrangedProduct1->getRoll()->getId());
        $this->assertTrue($manuallyArrangedProduct1->getRoll()->getId() === $manuallyArrangedProduct2->getRoll()->getId());
    }

    private function getAvailableFilm(): FilmData
    {
        /** @var AvailableFilmServiceInterface $availableFilms */
        $availableFilmsService = $this->getContainer()->get(AvailableFilmServiceInterface::class);
        /** @var Collection<FilmData> $availableFilms */
        $availableFilms = $availableFilmsService->getAvailableFilms();

        /* @var FilmData $firstAvailable */
        return $availableFilms->first();
    }

    private function prepareProduct(?string $filmType, float $length = 0, ?string $lamination = null): PrintedProduct
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
}
