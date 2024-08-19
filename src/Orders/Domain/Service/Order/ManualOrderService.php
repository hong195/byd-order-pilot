<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Factory\OrderFactory;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Infrastructure\Repository\MediaFileRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Constructs a new instance of the class.
 *
 * @param OrderFactory        $orderFactory        the order factory instance
 * @param OrderRepository     $orderRepository     the order repository instance
 * @param MediaFileRepository $mediaFileRepository the media file repository instance
 */
final readonly class ManualOrderService
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderFactory        $orderFactory        the order factory instance
     * @param OrderRepository     $orderRepository     the order repository instance
     * @param MediaFileRepository $mediaFileRepository the media file repository instance
     */
    public function __construct(private OrderFactory $orderFactory, private OrderRepository $orderRepository, private MediaFileRepository $mediaFileRepository)
    {
    }

    /**
     * Creates and saves a new order.
     *
     * @param string      $productType    the type of the product for the order
     * @param int         $length         the length of the order
     * @param string      $filmType       the type of the roll, if applicable, otherwise null
     * @param bool        $hasPriority    indicates whether the order has priority
     * @param string|null $laminationType the type of lamination, if applicable, otherwise null
     * @param string|null $orderNumber    the order number, if provided, otherwise null
     *
     * @return Order the created order
     */
    public function add(string $productType, int $length, string $filmType, bool $hasPriority, ?string $laminationType = null, ?string $orderNumber = null): Order
    {
        $order = $this->orderFactory->make(
            length: $length,
            productType: $productType,
            laminationType: $laminationType,
            filmType: $filmType,
            hasPriority: $hasPriority,
            orderNumber: $orderNumber
        );

        $this->orderRepository->save($order);

        return $order;
    }

    /**
     * Attaches a media file to an order.
     *
     * @param Order  $order    the order object to attach the media file to
     * @param int    $fileId   the ID of the media file to attach
     * @param string $fileType the type of the media file being attached
     *
     * @throws NotFoundHttpException if the media file with the given ID is not found
     */
    public function attachFile(Order $order, int $fileId, string $fileType): void
    {
        $mediaFile = $this->mediaFileRepository->findById($fileId);

        if (is_null($mediaFile)) {
            throw new NotFoundHttpException('Media file not found');
        }

        $method = $this->snakeToCamel("set_{$fileType}");

        $order->{$method}($mediaFile);

        $this->orderRepository->save($order);
    }

    /**
     * Converts a snake_case string to camelCase.
     *
     * @param string $string the snake_case string to convert
     *
     * @return string the camelCase string
     */
    private function snakeToCamel(string $string): string
    {
        $parts = explode('_', $string);

        $camelCaseString = array_shift($parts);

        foreach ($parts as $part) {
            $camelCaseString .= ucfirst(strtolower($part));
        }

        return $camelCaseString;
    }
}
