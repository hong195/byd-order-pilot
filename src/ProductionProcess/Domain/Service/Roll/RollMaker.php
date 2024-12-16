<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Factory\RollFactory;
use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;

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
     * @param RollRepositoryInterface $rollRepository the RollRepository instance
     * @param RollFactory             $rollFactory    the RollFactory instance
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private RollFactory $rollFactory)
    {
    }

    /**
     * Creates a new Roll.
     *
     * @param string   $name   The name of the roll
     * @param int|null $filmId The ID of the film associated with the roll
     *
     * @return Roll The created Roll object
     */
    public function make(string $name, ?string $filmId = null): Roll
    {
        $roll = $this->rollFactory->create($name, $filmId, Process::ORDER_CHECK_IN);

        $this->rollRepository->save($roll);

        return $roll;
    }
}
