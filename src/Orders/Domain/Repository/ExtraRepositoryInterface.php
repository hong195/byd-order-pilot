<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Extra;

/**
 * Interface ExtraRepositoryInterface.
 *
 * Represents a repository for storing Extra objects.
 */
interface ExtraRepositoryInterface
{
    /**
     * Adds an Extra to the application.
     *
     * @param Extra $extra the Extra to be added
     */
    public function add(Extra $extra): void;
}
