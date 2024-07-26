<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Service;

use App\Rolls\Domain\Aggregate\Order\Order;
use App\Rolls\Domain\Factory\OrderFactory;
use App\Rolls\Infrastructure\Repository\OrderRepository;
use App\Shared\Infrastructure\Repository\MediaFileRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ManualOrderService
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderFactory        $orderFactory        the order factory instance
     * @param OrderRepository     $orderRepository     the order repository instance
     * @param MediaFileRepository $mediaFileRepository the media file repository instance
     */
    public function __construct(
        private OrderFactory $orderFactory,
        private OrderRepository $orderRepository,
        private MediaFileRepository $mediaFileRepository
    ) {
    }

    /**
     * Adds a new order.
     *
     * @param string      $priority       the priority of the order
     * @param string      $productType    the product type of the order
     * @param string|null $laminationType the lamination type of the order (optional)
     * @param string|null $rollType       the roll type of the order (optional)
     * @param string|null $orderNumber    the order number of the order (optional)
     *
     * @return Order the newly created order
     */
    public function add(
        string $priority,
        string $productType,
        ?string $laminationType = null,
        ?string $rollType = null,
        ?string $orderNumber = null): Order
    {
        $order = $this->orderFactory->make(
            $priority,
            $productType,
            $laminationType,
            $rollType,
            $orderNumber
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
