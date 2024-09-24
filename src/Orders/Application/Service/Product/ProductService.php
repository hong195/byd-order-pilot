<?php

declare(strict_types=1);

namespace App\Orders\Application\Service\Product;

use App\Orders\Application\DTO\Product\ProductCreateDTO;
use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\Factory\ProductFactory;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Infrastructure\Repository\MediaFileRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ProductService
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderRepository     $orderRepository     the order repository instance
     * @param MediaFileRepository $mediaFileRepository the media file repository instance
     */
    public function __construct(private OrderRepository $orderRepository, private ProductFactory $productFactory, private ProductRepositoryInterface $productRepository, private MediaFileRepository $mediaFileRepository)
    {
    }

    public function create(ProductCreateDTO $dto): Product
    {
        $order = $this->orderRepository->findById($dto->orderId);

        if (is_null($order)) {
            throw new NotFoundHttpException('Order not found');
        }

        $product = $this->productFactory->make(
            filmType: FilmType::from($dto->filmType),
            length: $dto->length,
            laminationType: $dto->laminationType ? LaminationType::from($dto->laminationType) : null
        );

        $cutFile = $dto->cutFileId ? $this->mediaFileRepository->findById($dto->cutFileId) : null;
        $printFile = $dto->printFileId ? $this->mediaFileRepository->findById($dto->printFileId) : null;

        if ($cutFile) {
            $product->setCutFile($cutFile);
        }

        if ($printFile) {
            $product->setPrintFile($printFile);
        }

        $this->productRepository->add($product);

        $order->addProduct($product);

        $this->orderRepository->save($order);

		return $product;
    }
}
