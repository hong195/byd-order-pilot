<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\OrdersCheckInProcess;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Roll;
use App\Orders\Domain\DTO\FilmData;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\RollFilter;
use App\Orders\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\Orders\Domain\Service\Order\SortOrdersServiceInterface;
use App\Orders\Domain\Service\RollMaker;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\Process;
use App\Orders\Domain\ValueObject\Status;
use App\Orders\Infrastructure\Repository\RollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class MaxMinReArrangeOrderService.
 */
final class OrdersOrdersCheckInService implements OrdersCheckInInterface
{
    private Collection $assignedRolls;

    private Collection $rolls;

    private Collection $orders;

    /**
     * Class constructor.
     *
     * @param OrderRepositoryInterface      $orderRepository      The order repository
     * @param SortOrdersServiceInterface    $sortOrdersService    The sort orders service
     * @param RollRepository                $rollRepository       The roll repository
     * @param AvailableFilmServiceInterface $availableFilmService The available film service
     * @param RollMaker                     $rollMaker            The roll maker
     */
    public function __construct(private readonly OrderRepositoryInterface $orderRepository, private readonly SortOrdersServiceInterface $sortOrdersService, private readonly RollRepository $rollRepository, private readonly AvailableFilmServiceInterface $availableFilmService, private readonly RollMaker $rollMaker)
    {
        $this->assignedRolls = new ArrayCollection([]);

        $this->rolls = new ArrayCollection($this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN)));

        $this->initOrders();
    }

    /**
     * Performs the check-in process for the current session.
     *
     * @throws \Exception if an error occurs during the check-in process
     */
    public function checkIn(): void
    {
        $availableFilms = $this->availableFilmService->getAvailableFilms();
        $groupedFilms = $this->groupFilmsByType($availableFilms);
        $groupedOrders = $this->groupOrdersByFilm($this->orders);

        foreach ($groupedOrders as $filmType => $orders) {
            if (!isset($groupedFilms[$filmType])) {
                // If there is no film of this type, create an empty roll for all orders of this type
                foreach ($orders as $order) {
                    $roll = $this->findOrMakeRoll(name: "Empty Roll {$order->getFilmType()->value}", filmId: null, filmType: $order->getFilmType());
                    $roll->addOrder($order);
                    $this->syncAssignRolls($roll);
                }
                continue;
            }

            // Инициализируем доступные пленки для данного типа
            $currentFilm = $groupedFilms[$filmType];

            foreach ($orders as $order) {
                $orderPlaced = false;

                // Attempting to place an order on existing film rolls
                foreach ($currentFilm as $key => $film) {
                    $filmLength = $film->length;

                    $roll = $this->findOrMakeRoll(name: "Roll {$film->filmType}", filmId: $film->id, filmType: $order->getFilmType());

                    if ($roll->getOrdersLength() + $order->getLength() <= $filmLength) {
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
                    $roll = $this->findOrMakeRoll("Empty Roll {$order->getFilmType()->value}", null, $order->getFilmType());
                    $roll->getOrders()->count();
                    $roll->addOrder($order);

                    $this->syncAssignRolls($roll);
                }
            }
        }

        $this->rollRepository->saveRolls($this->assignedRolls);
    }

    /**
     * Finds or makes a roll based on the given parameters.
     *
     * @param string        $name     The name of the roll
     * @param int|null      $filmId   The ID of the film associated with the roll (optional)
     * @param FilmType|null $filmType The roll type associated with the roll (optional)
     *
     * @return Roll The found or newly created roll
     */
    private function findOrMakeRoll(string $name, ?int $filmId = null, ?FilmType $filmType = null): Roll
    {
        $roll = $this->rolls->filter(function (Roll $roll) use ($filmId, $filmType) {
            return $roll->getFilmId() === $filmId && in_array($filmType, $roll->getPrinter()->getFilmTypes());
        })->first();

        if ($roll) {
            return $roll;
        }

        $roll = $this->rollMaker->make($name, $filmId, $filmType, Process::ORDER_CHECK_IN);

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
            $groupedOrders[$order->getFilmType()->value][] = $order;
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
            $groupedFilms[$film->filmType][] = $film;
        }

        return $groupedFilms;
    }

    /**
     * Initializes the orders in the application.
     *
     * This method retrieves the orders with status "assignable" from the order repository,
     * adds them to the $orders collection, and then adds the orders associated with each
     * roll in the $rolls collection to the $orders collection. Finally, it sorts the
     * $orders collection using the SortOrdersService.
     */
    private function initOrders(): void
    {
        $this->orders = new ArrayCollection();
        $assignableOrders = $this->orderRepository->findByStatus(Status::ASSIGNABLE);

        foreach ($assignableOrders as $order) {
            $this->orders->add($order);
        }

        foreach ($this->rolls as $roll) {
            foreach ($roll->getOrders() as $order) {
                $this->orders->add($order);
            }

            $roll->removeOrders();
        }

        $this->orders = $this->sortOrdersService->getSorted($this->orders);
    }
}
