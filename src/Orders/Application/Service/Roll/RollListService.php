<?php

declare(strict_types=1);

namespace App\Orders\Application\Service\Roll;

use App\Orders\Application\DTO\EmployeeData;
use App\Orders\Application\DTO\PrinterData;
use App\Orders\Application\DTO\PrinterDataTransformer;
use App\Orders\Application\DTO\RollData;
use App\Orders\Application\DTO\RollDataTransformer;
use App\Orders\Application\Service\Employee\EmployeeFetcher;
use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;
use App\Orders\Domain\Repository\RollFilter;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\ValueObject\Process;

final readonly class RollListService
{
    public function __construct(private RollRepositoryInterface $rollRepository, private EmployeeFetcher $employeeFetcher,
        private PrinterRepositoryInterface $printerRepository,
        private RollDataTransformer $rollDataTransformer, private PrinterDataTransformer $printerDataTransformer)
    {
    }

    /**
     * Retrieves rolls data along with employee information for a given process.
     *
     * @param Process $process the process to get rolls data for
     *
     * @return RollData[] the rolls data along with employee information
     */
    public function getList(Process $process): array
    {
        $filter = new RollFilter(process : $process);

        $rolls = $this->rollRepository->findByFilter($filter);
        $employees = $this->employeeFetcher->getByIds(array_map(fn ($roll) => $roll->getEmployeeId(), $rolls));
        $printers = $this->printerRepository->all();

        $rollsData = [];

        foreach ($rolls as $roll) {
            $employee = $employees->filter(fn (EmployeeData $employee) => $employee->id === $roll->getEmployeeId())->first();
            $printer = $printers->filter(fn (Printer $printer) => $printer->getId() === $roll->getPrinter()?->getId())->first();

            if ($printer) {
                $printer = new PrinterData(
                    id: $printer->getId(),
                    name: $printer->getName(),
                );
            }

            $data = $this->rollDataTransformer->fromEntity($roll);

            $data->withEmployee($employee ?: null);
            $data->withPrinter($printer ?: null);

            $rollsData[] = $data;
        }

        return $rollsData;
    }

    public function getSingle(int $id): RollData
    {
        $roll = $this->rollRepository->findById($id);

        $data = $this->rollDataTransformer->fromEntity($roll);

        $employee = $this->employeeFetcher->getById($roll->getEmployeeId());
        $printer = $this->printerRepository->findById($roll->getPrinter()?->getId());

        $data->withEmployee($employee);
        $data->withPrinter($printer);

        return $data;
    }
}
