<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Factory\RollFactory;
use App\ProductionProcess\Domain\Repository\PrinterRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
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
     * @param RollRepositoryInterface    $rollRepository    the RollRepository instance
     * @param RollFactory                $rollFactory       the RollFactory instance
     * @param PrinterRepositoryInterface $printerRepository the PrinterRepositoryInterface instance
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private RollFactory $rollFactory, private PrinterRepositoryInterface $printerRepository)
    {
    }

    /**
     * Creates a new Roll.
     *
     * @param string      $name     The name of the roll
     * @param int|null    $filmId   The ID of the film associated with the roll
     * @param string|null $filmType The type of the film associated with the roll
     *
     * @return Roll The created Roll object
     */
    public function make(string $name, ?int $filmId = null, ?string $filmType = null): Roll
    {
        $roll = $this->rollFactory->create($name, $filmId, Process::ORDER_CHECK_IN);

        if ($filmType) {
            $printer = $this->printerRepository->findByFilmType($filmType);

            if ($printer) {
                $roll->assignPrinter($printer);
            }
        }

        $this->rollRepository->save($roll);

        return $roll;
    }
}
