<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Roll;

use App\Orders\Domain\Aggregate\Roll\Roll;
use App\Orders\Domain\Events\RollWasCreatedEvent;
use App\Orders\Domain\Factory\RollFactory;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\Process;
use App\Orders\Infrastructure\Repository\RollRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

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
    public function __construct(private RollRepository $rollRepository, private RollFactory $rollFactory, private PrinterRepositoryInterface $printerRepository, private EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * Get the name of a person.
     *
     * @param string    $name     The name of the person
     * @param ?int      $filmId   Film Id
     * @param ?FilmType $filmType roll type
     *
     * @return Roll The name of the person
     */
    public function make(string $name, ?int $filmId = null, ?FilmType $filmType = null, ?Process $process = null): Roll
    {
        $roll = $this->rollFactory->create($name, $filmId, $process);

        if ($filmType) {
            $printer = $this->printerRepository->findByfilmType($filmType);

            if ($printer) {
                $roll->assignPrinter($printer);
            }
        }

        $this->rollRepository->save($roll);

        $this->dispatcher->dispatch(new RollWasCreatedEvent($roll->getId()));

        return $roll;
    }
}
