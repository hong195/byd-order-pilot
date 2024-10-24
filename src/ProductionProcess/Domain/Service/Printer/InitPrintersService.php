<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Printer;

use App\ProductionProcess\Domain\Factory\PrinterFactory;
use App\ProductionProcess\Domain\Repository\PrinterRepositoryInterface;

final readonly class InitPrintersService
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

    public function init(): void
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
