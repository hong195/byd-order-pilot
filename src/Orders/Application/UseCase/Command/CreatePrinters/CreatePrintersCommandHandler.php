<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\CreatePrinters;

use App\Orders\Domain\Aggregate\Roll\RollType;
use App\Orders\Domain\Aggregate\ValueObject\LaminationType;
use App\Orders\Domain\Aggregate\ValueObject\PrinterType;
use App\Orders\Domain\Factory\PrinterFactory;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

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
        $printers = $this->printerRepository->findByNames(array_column(PrinterType::cases(), 'value'));

        if (3 === count($printers)) {
            return;
        }

        $printers = [
            [
                'name' => PrinterType::ROLAND_UV_PRINTER,
                'rollTypes' => [RollType::WHITE],
                'laminationTypes' => LaminationType::cases(),
            ],
            [
                'name' => PrinterType::MIMAKI_UV_PRINTER,
                'rollTypes' => [RollType::CHROME, RollType::NEON, RollType::CLEAR],
                'laminationTypes' => LaminationType::cases(),
            ],
            [
                'name' => PrinterType::ROLAND_NORMAL_PRINTER,
                'rollTypes' => [RollType::ECO],
                'laminationTypes' => [],
            ],
        ];

        foreach ($printers as $printerData) {
            $printer = $this->printerFactory->make($printerData['name'], $printerData['rollTypes'], $printerData['laminationTypes']);
            $this->printerRepository->save($printer);
        }

        return;
    }
}
