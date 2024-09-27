<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CreatePrinters;

use App\ProductionProcess\Domain\Factory\PrinterFactory;
use App\ProductionProcess\Domain\Repository\PrinterRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\FilmType;
use App\ProductionProcess\Domain\ValueObject\LaminationType;
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
                'filmTypes' => ['white'],
                'laminationTypes' => ['gold_flakes', 'holo_flakes', 'matt', 'glossy'],
            ],
            [
                'name' => 'Mimaki UV Printer',
                'filmTypes' => ['chrome', 'neon', 'clear'],
				'laminationTypes' => ['gold_flakes', 'holo_flakes', 'matt', 'glossy'],
            ],
            [
                'name' => 'Roland Normal Printer',
                'filmTypes' => ['eco'],
                'laminationTypes' => [],
            ],
        ];

        foreach ($printers as $printerData) {
            $printer = $this->printerFactory->make($printerData['name'], $printerData['filmTypes'], $printerData['laminationTypes']);
            $this->printerRepository->save($printer);
        }
    }
}
