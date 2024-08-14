<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\CheckInProcess;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Roll;
use App\Orders\Domain\DTO\FilmData;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\RollFilter;
use App\Orders\Domain\Service\AvailableFilmServiceInterface;
use App\Orders\Domain\Service\Order\SortOrdersServiceInterface;
use App\Orders\Domain\Service\RollMaker;
use App\Orders\Domain\ValueObject\Process;
use App\Orders\Domain\ValueObject\RollType;
use App\Orders\Domain\ValueObject\Status;
use App\Orders\Infrastructure\Repository\RollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class MaxMinReArrangeOrderService.
 */
final class OrdersCheckInService implements CheckInInterface
{
    private Collection $assignedRolls;

    private Collection $rolls;

    /**
     * Constructor for the class.
     *
     * @param OrderRepositoryInterface $orderRepository the order repository service
     * @param RollRepository           $rollRepository  the roll repository
     */
    public function __construct(private readonly OrderRepositoryInterface $orderRepository, private readonly SortOrdersServiceInterface $sortOrdersService,
        private readonly RollRepository $rollRepository, private readonly AvailableFilmServiceInterface $availableFilmService, private readonly RollMaker $rollMaker
    ) {
        $this->assignedRolls = new ArrayCollection([]);

        $this->rolls = new ArrayCollection($this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN)));

        foreach ($this->rolls as $roll) {
            $roll->removeOrders();
        }
    }

    /**
     * Performs the check-in process for the current session.
     *
     * @throws \Exception if an error occurs during the check-in process
     */
    public function checkIn(): void
    {
        $allOrders = $this->sortOrdersService->getSorted(new ArrayCollection($this->orderRepository->findByStatus(Status::ASSIGNED)));

        $availableFilms = $this->availableFilmService->getAvailableFilms();
        $groupedFilms = $this->groupFilmsByType($availableFilms);
        $groupedOrders = $this->groupOrdersByFilm($allOrders);

        foreach ($groupedOrders as $rollType => $orders) {
            if (!isset($groupedFilms[$rollType])) {
                // If there is no film of this type, create an empty roll for all orders of this type
                foreach ($orders as $order) {
                    $roll = $this->findOrMakeRoll(name: "Empty Roll {$order->getRollType()->value}", filmId: null, rollType: $order->getRollType());
                    $this->changeStatusToAssign($order);
                    $roll->addOrder($order);
                    $this->syncAssignRolls($roll);
                }
                continue;
            }

            // Инициализируем доступные пленки для данного типа
            $currentFilm = $groupedFilms[$rollType];

            foreach ($orders as $order) {
                $orderPlaced = false;

                // Attempting to place an order on existing film rolls
                foreach ($currentFilm as $key => $film) {
                    $filmLength = $film->length;

                    $roll = $this->findOrMakeRoll(name: "Roll {$film->rollType}", filmId: $film->id, rollType: $order->getRollType());

                    if ($roll->getOrdersLength() + $order->getLength() <= $filmLength) {
                        $this->changeStatusToAssign($order);
                        $roll->addOrder($order);

                        $this->syncAssignRolls($roll);
                        $orderPlaced = true;

                        if (0 === $filmLength) {
                            unset($currentFilm[$key]); // Remove the film from the available films
                        }

                        break;
                    }
                }

                // Если заказ не был размещен, создаем пустой рулон
                if (!$orderPlaced) {
                    $roll = $this->findOrMakeRoll("Empty Roll {$order->getRollType()->value}", null, $order->getRollType());
                    $roll->getOrders()->count();
                    $this->changeStatusToAssign($order);
                    $roll->addOrder($order);

                    $this->syncAssignRolls($roll);
                }
            }
        }

        $this->rollRepository->saveRolls($this->assignedRolls);
    }

    private function changeStatusToAssign(Order $order): void
    {
        $order->changeStatus(Status::ASSIGNED);
        $this->orderRepository->save($order);
    }

    /**
     * Finds or makes a roll based on the given parameters.
     *
     * @param string        $name     The name of the roll
     * @param int|null      $filmId   The ID of the film associated with the roll (optional)
     * @param RollType|null $rollType The roll type associated with the roll (optional)
     *
     * @return Roll The found or newly created roll
     */
    private function findOrMakeRoll(string $name, ?int $filmId = null, ?RollType $rollType = null): Roll
    {
        $roll = $this->rolls->filter(function (Roll $roll) use ($filmId, $rollType) {
            return $roll->getFilmId() === $filmId && in_array($rollType, $roll->getPrinter()->getRollTypes());
        })->first();

        if ($roll) {
            return $roll;
        }

        $roll = $this->rollMaker->make($name, $filmId, $rollType);

        $this->rolls->add($roll);

        return $roll;
    }

    /**
     * Syncs the assigned rolls with a new roll.
     *
     * @param Roll $roll The roll to sync with
     */
    private function syncAssignRolls(Roll $roll): void
    {
        // if roll was added previously to assignedRolls, remove it and add it again
        if ($this->assignedRolls->contains($roll)) {
            $this->assignedRolls->removeElement($roll);
        }

        $this->assignedRolls->add($roll);
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
