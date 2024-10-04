<?php

namespace App\ProductionProcess\Infrastructure\Repository\InMemory;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;

/**
 * Repository class for Roll entities.
 * Extends the ServiceEntityRepository class and implements the RollRepositoryInterface.
 */
class RollRepository implements RollRepositoryInterface
{
    private array $rolls = [];

    /**
     * Add a roll to the database.
     *
     * @param Roll $roll the roll to add
     */
    public function add(Roll $roll): void
    {
        $this->rolls[] = $roll;
    }

    /**
     * Finds a roll by its ID.
     *
     * @param int $id the ID of the roll to find
     *
     * @return Roll|null the found roll, or null if no roll was found
     */
    public function findById(int $id): ?Roll
    {
        return array_filter($this->rolls, function (Roll $roll) use ($id) {
            return $roll->getId() === $id;
        })[0] ?? null;
    }

    /**
     * Saves a Roll entity.
     *
     * @param Roll $roll The Roll entity to save
     */
    public function save(Roll $roll): void
    {
        foreach ($this->rolls as $key => $r) {
            if ($r->getId() === $roll->getId()) {
                $this->rolls[$key] = $roll;
            }
        }
    }

    /**
     * Removes a Roll entity.
     *
     * @param Roll $roll The Roll entity to remove
     */
    public function remove(Roll $roll): void
    {
        $this->rolls = array_filter($this->rolls, function (Roll $r) use ($roll) {
            return $r->getId() !== $roll->getId();
        });
    }

    /**
     * Finds rolls based on the given filter.
     *
     * @param RollFilter $rollFilter the filter for rolls
     *
     * @return Roll[] the array of rolls found
     */
    public function findByFilter(RollFilter $rollFilter): array
    {
        return array_filter($this->rolls, function (Roll $roll) use ($rollFilter) {
            if (!empty($rollFilter->filmIds) && !in_array($roll->getFilmId(), $rollFilter->filmIds)) {
                return false;
            }

            if ($rollFilter->filmType) {
                $printerTypes = json_decode($roll->getPrinter()?->getFilmTypes(), true);
                if (!in_array($rollFilter->filmType, $printerTypes)) {
                    return false;
                }
            }

            if ($rollFilter->process && ($roll->getProcess()->value != $rollFilter->process->value)) {
                return false;
            }

            return true;
        });
    }

    /**
     * Finds a roll by its film ID.
     *
     * If a film ID is provided, it will return the first roll that matches the film ID.
     * If the film ID is not provided or the roll is not found, it will return null.
     *
     * @param int|null $filmId the ID of the film to search for
     *
     * @return Roll|null the found roll, or null if no roll was found
     */
    public function findByFilmId(?int $filmId = null): ?Roll
    {
        return array_filter($this->rolls, function (Roll $roll) use ($filmId) {
            return $roll->getFilmId() === $filmId;
        })[0] ?? null;
    }

    /**
     * Saves multiple rolls.
     *
     * @param iterable<Roll> $rolls the rolls to save
     */
    public function saveRolls(iterable $rolls): void
    {
        foreach ($rolls as $roll) {
            if ($this->findById($roll->getId())) {
                $this->save($roll);
            } else {
                $this->add($roll);
            }
        }
    }
}
