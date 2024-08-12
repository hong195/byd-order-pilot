<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\OrderCheckInProcess;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\DTO\FilmData;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Service\AvailableFilmServiceInterface;
use App\Orders\Domain\Service\RollMaker;
use App\Orders\Domain\ValueObject\Status;
use App\Orders\Infrastructure\Repository\RollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class MaxMinReArrangeOrderService.
 */
final readonly class OrdersCheckInService implements OrderCheckInInterface
{
    /**
     * Constructor for the class.
     *
     * @param OrderRepositoryInterface $orderRepository the order repository service
     * @param RollRepository           $rollRepository  the roll repository
     */
    public function __construct(private OrderRepositoryInterface $orderRepository, private SortOrdersServiceInterface $sortOrdersService,
        private RollRepository $rollRepository, private AvailableFilmServiceInterface $availableFilmService, private RollMaker $rollMaker
    ) {
    }

    public function checkIn(): void
    {
        /** @var Collection<Order> $orders */
        $allOrders = $this->sortOrdersService->getSorted(new ArrayCollection($this->orderRepository->findByStatus(Status::ORDER_CHECK_IN)));
        $availableFilms = new ArrayCollection();
        $groupedFilms = $this->groupFilmsByType($availableFilms);
        $groupedOrders = $this->groupOrdersByFilm($allOrders);

        $assignedRolls = [];

        foreach ($groupedOrders as $rollType => $orders) {
            if (!isset($groupedFilms[$rollType])) {
                // Если пленки этого типа нет, создаем пустой рулон для всех заказов этого типа
                foreach ($orders as $order) {
                    $roll = $this->rollMaker->make("Empty Roll {$order->getRollType()->value}", null, $order->getRollType());
                    $roll->addOrder($order);
                    $assignedRolls[] = $roll;
                }
                continue;
            }

            // Инициализируем доступные пленки для данного типа
            $currentFilm = $groupedFilms[$rollType];

            foreach ($orders as $order) {
                $orderPlaced = false;

                // Попытка разместить заказ на существующих рулонах с пленкой
                foreach ($currentFilm as $key => $film) {
                    if ($film->length >= $order->getLength()) {
                        $roll = $this->rollMaker->findOrMake("Roll {$film->rollType}", $film->id, $order->getRollType());
                        $roll->addOrder($order);
                        $film->length -= $order->getLength(); // Уменьшаем доступную длину пленки
                        $assignedRolls[] = $roll;
                        $orderPlaced = true;

                        if (0 === $film->length) {
                            unset($currentFilm[$key]); // Удаляем пленку, если она закончилась
                        }

                        break;
                    }
                }

                // Если заказ не был размещен, создаем пустой рулон
                if (!$orderPlaced) {
                    $roll = createEmptyRoll();
                    $roll->addOrder($order);
                    $assignedRolls[] = $roll;
                }
            }
        }
    }

    /**
     * Groups orders by roll type.
     *
     * @param Collection<Order> $orders the collection of orders
     *
     * @return array<string, Order[]> the array of grouped orders
     */
    private function groupOrdersByFilm(Collection $orders): array
    {
        $groupedOrders = [];

        foreach ($orders as $order) {
            $groupedOrders[$order->getRollType()->value][] = $order;
        }

        return $groupedOrders;
    }

    /**
     * Groups films by roll type.
     *
     * @param Collection<FilmData> $films the collection of films
     *
     * @return array<string, FilmData[]> the array of grouped films
     */
    private function groupFilmsByType(Collection $films): array
    {
        $groupedFilms = [];

        foreach ($films as $film) {
            $groupedFilms[$film->rollType][] = $film;
        }

        return $groupedFilms;
    }
}
