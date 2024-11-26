<?php

namespace App\Tests\Functional\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\TakeAPhotoService;
use App\Shared\Domain\Repository\MediaFileRepositoryInterface;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TakeAPhotoServiceTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private TakeAPhotoService $takeAPhotoService;
    private PrintedProductRepositoryInterface $printedProductRepository;
    private RollRepositoryInterface $rollRepository;
    private MediaFileRepositoryInterface $mediaFileRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->takeAPhotoService = self::getContainer()->get(TakeAPhotoService::class);
        $this->printedProductRepository = self::getContainer()->get(PrintedProductRepositoryInterface::class);
        $this->rollRepository = self::getContainer()->get(RollRepositoryInterface::class);
        $this->mediaFileRepo = $this->getContainer()->get(MediaFileRepositoryInterface::class);
    }

    public function test_can_upload_photo(): void
    {
        $printedProduct = $this->loadPrintedProduct();
        $mediaFile = $this->loadMediaFile();

        $this->takeAPhotoService->upload($printedProduct->getId(), $mediaFile->getId());

        $refreshedProduct = $this->printedProductRepository->findById($printedProduct->getId());
        $refreshedFile = $this->mediaFileRepo->findById($mediaFile->getId());

        $this->assertEquals($refreshedFile->getId(), $refreshedProduct->getPhoto()->getId());
    }

    public function test_it_remove_old_photo_if_product_has(): void
    {
        $printedProduct = $this->loadPrintedProduct();
        $mediaFile1 = $this->loadMediaFile();
        $mediaFile1Id = $mediaFile1->getId();
        $this->takeAPhotoService->upload($printedProduct->getId(), $mediaFile1->getId());

        $mediaFile2 = $this->loadMediaFile();

        $this->takeAPhotoService->upload($printedProduct->getId(), $mediaFile2->getId());

        $refreshedProduct = $this->printedProductRepository->findById($printedProduct->getId());
        $removedMediaFile = $this->mediaFileRepo->findById($mediaFile1Id);

        $this->assertNull($removedMediaFile);
        $this->assertNotNull($refreshedProduct->getPhoto());
        $this->assertEquals($mediaFile2->getId(), $refreshedProduct->getPhoto()->getId());
    }

    public function test_it_throws_exception(): void
    {
        $notExistingProductId = 999;
        $mediaFile1 = $this->loadMediaFile();

        $this->expectException(NotFoundHttpException::class);

        $this->takeAPhotoService->upload($notExistingProductId, $mediaFile1->getId());
    }
}
