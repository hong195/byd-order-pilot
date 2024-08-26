<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\CreatePrinters;

use App\Orders\Domain\Factory\PrinterFactory;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class CreatePrintersCommandHandler.
 */
readonly class CreatePrintersCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructor.
     *
     * @param PrinterRepositoryInterface $printerRepository the printer repository
     * @param PrinterFactory             $printerFactory    the printer factory
     */
    public function __construct(private PrinterRepositoryInterface $printerRepository, private PrinterFactory $printerFactory)
    {
    }

    /**
     * Invokes the command to create printers.
     *
     * @param CreatePrintersCommand $createPrintersCommand the create printers command
     *
     * @throws \Exception if not allowed to handle resource
     */
    public function __invoke(CreatePrintersCommand $createPrintersCommand): void
    {
        $printers = [
            [
                'name' => 'Roland UV Printer',
                'filmTypes' => [FilmType::WHITE],
                'laminationTypes' => LaminationType::cases(),
            ],
            [
                'name' => 'Mimaki UV Printer',
                'filmTypes' => [FilmType::CHROME, FilmType::NEON, FilmType::CLEAR],
                'laminationTypes' => LaminationType::cases(),
            ],
            [
                'name' => 'Roland Normal Printer',
                'filmTypes' => [FilmType::ECO],
                'laminationTypes' => [],
            ],
        ];

        foreach ($printers as $printerData) {
            $printer = $this->printerFactory->make($printerData['name'], $printerData['filmTypes'], $printerData['laminationTypes']);
            $this->printerRepository->save($printer);
        }
    }
}
