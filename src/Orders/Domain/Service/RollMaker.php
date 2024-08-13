<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Aggregate\Roll;
use App\Orders\Domain\Factory\RollFactory;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;
use App\Orders\Domain\Repository\RollFilter;
use App\Orders\Domain\ValueObject\RollType;
use App\Orders\Infrastructure\Repository\RollRepository;

/**
 * Class RollMaker.
 *
 * Description: This class is responsible for creating rolls using the RollFactory, assigning a printer to the roll, and saving it to the RollRepository.
 */
final readonly class RollMaker
{
    /**
     * Class constructor.
     *
     * @param RollRepository             $rollRepository    the RollRepository instance
     * @param RollFactory                $rollFactory       the RollFactory instance
     * @param PrinterRepositoryInterface $printerRepository the PrinterRepositoryInterface instance
     */
    public function __construct(private RollRepository $rollRepository, private RollFactory $rollFactory, private PrinterRepositoryInterface $printerRepository)
    {
    }

    /**
     * Get the name of a person.
     *
     * @param string    $name     The name of the person
     * @param ?int      $filmId   Film Id
     * @param ?RollType $rollType roll type
     *
     * @return Roll The name of the person
     */
    public function make(string $name, ?int $filmId = null, ?RollType $rollType = null): Roll
    {
        $roll = $this->rollFactory->create($name, $filmId);
        $printer = $this->printerRepository->findByRollType($rollType);

        if ($printer) {
            $roll->assignPrinter($printer);
        }

        $this->rollRepository->save($roll);

        return $roll;
    }

    /**
     * Find an existing Roll or create a new one.
     *
     * @param string        $name     The name of the roll
     * @param int           $filmId   The ID of the film associated with the roll
     * @param RollType|null $rollType The type of the roll (optional)
     *
     * @return Roll The Roll entity that was found or created
     */
    public function findOrMake(string $name, ?int $filmId = null, ?RollType $rollType = null): Roll
    {
        $rollFilter = new RollFilter(filmIds: $filmId ? [$filmId] : [], rollType: $rollType->value);

        $roll = $this->rollRepository->findByFilter($rollFilter)[0] ?? null;

        if ($roll) {
            return $roll;
        }

        return $this->make($name, $filmId, $rollType);
    }
}
