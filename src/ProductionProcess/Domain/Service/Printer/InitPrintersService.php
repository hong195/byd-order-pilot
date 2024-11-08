<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Printer;

use App\ProductionProcess\Domain\Aggregate\Printer\Condition;
use App\ProductionProcess\Domain\Factory\PrinterFactory;
use Doctrine\ORM\EntityManagerInterface;

final readonly class InitPrintersService
{
    /**
     * Constructor for the class.
     *
     * @param PrinterFactory         $printerFactory an instance of PrinterFactory
     * @param EntityManagerInterface $entityManager  an instance of EntityManagerInterface
     */
    public function __construct(private PrinterFactory $printerFactory, private EntityManagerInterface $entityManager)
    {
    }

    public function init(): void
    {
        $printers = [
            [
                'name' => 'Roland UV Printer',
                'default' => false,
                'conditions' => [
                    [
                        'film_type' => 'white',      // Тип плёнки
                        'lamination_type' => 'matte', // Тип ламинации
                        'lamination_required' => true, // Ламинация обязательна
                    ],
                    [
                        'film_type' => 'white',
                        'lamination_type' => 'glossy',
                        'lamination_required' => true,
                    ],
                    [
                        'film_type' => 'white',
                        'lamination_type' => 'holo_flakes',
                        'lamination_required' => true,
                    ],
                    [
                        'film_type' => 'white',
                        'lamination_type' => 'gold_flakes',
                        'lamination_required' => true,
                    ],
                ],
            ],
            [
                'name' => 'Mimaki UV Printer',
                'default' => false,
                'conditions' => [
                    [
                        'film_type' => 'chrome',
                        'lamination_type' => null,    // Тип ламинации не важен
                        'lamination_required' => false, // Ламинация не обязательна
                    ],
                    [
                        'film_type' => 'neon',
                        'lamination_type' => null,
                        'lamination_required' => false,
                    ],
                    [
                        'film_type' => 'clear',
                        'lamination_type' => null,
                        'lamination_required' => false,
                    ],
                ],
            ],
            [
                'name' => 'Roland Normal Printer',
                'default' => true,
                'conditions' => [
                ],
            ],
        ];

        foreach ($printers as $printerData) {
            $printer = $this->printerFactory->make(
                name: $printerData['name'],
                isDefault: $printerData['default']
            );

            $this->entityManager->persist($printer);

            foreach ($printerData['conditions'] as $conditionData) {
                $condition = new Condition(
                    printer: $printer,
                    filmType: $conditionData['film_type'],
                    laminationType: $conditionData['lamination_type'],
					laminationRequired: $conditionData['lamination_required']
                );

                $this->entityManager->persist($condition);
            }

            $this->entityManager->flush();
        }
    }
}
