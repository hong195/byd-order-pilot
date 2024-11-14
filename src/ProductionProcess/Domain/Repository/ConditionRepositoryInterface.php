<?php

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\Aggregate\Printer\Condition;
use Doctrine\Common\Collections\Collection;

/**
 * Interface ConditionRepositoryInterface.
 */
interface ConditionRepositoryInterface
{
    public function add(Condition $condition): void;

    /**
     * Retrieve all data from the database and return as a Collection.
     *
     * @return Collection<Condition>
     */
    public function all(): Collection;
}
