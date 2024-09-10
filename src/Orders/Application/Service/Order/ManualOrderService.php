<?php

declare(strict_types=1);

namespace App\Orders\Application\Service\Order;

use App\Orders\Application\DTO\ManualCreateOrderDTO;
use App\Orders\Domain\Aggregate\Customer;
use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Factory\OrderFactory;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Orders\Domain\ValueObject\Status;
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
    private const ORDER_MANUAL_PREFIX = 'MANUAL';

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
     * @return Order the created order
     */
    public function add(ManualCreateOrderDTO $orderData): Order
    {
        $this->orderFactory->withStatus(Status::UNASSIGNED);
        $this->orderFactory->withLamination(LaminationType::from($orderData->laminationType));
        $this->orderFactory->withPackagingInstructions($orderData->packagingInstructions);

        $order = $this->orderFactory->make(
            customer: new Customer(name: $orderData->customerName, notes: $orderData->customerNotes),
            length: $orderData->length,
            filmType: FilmType::from($orderData->filmType),
        );

        $this->orderRepository->save($order);

        $order->changeOrderNumber(self::ORDER_MANUAL_PREFIX.'-'.$order->getId());

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
