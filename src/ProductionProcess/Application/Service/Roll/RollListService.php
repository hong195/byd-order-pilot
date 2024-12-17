<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\Service\Roll;

use App\ProductionProcess\Application\DTO\EmployeeData;
use App\ProductionProcess\Application\DTO\Printer\PrinterData;
use App\ProductionProcess\Application\DTO\Roll\RollData;
use App\ProductionProcess\Application\DTO\Roll\RollDataTransformer;
use App\ProductionProcess\Application\Service\Employee\EmployeeFetcher;
use App\ProductionProcess\Domain\Aggregate\Printer\Printer;
use App\ProductionProcess\Domain\Repository\Printer\PrinterRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollFilter;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class RollListService
{
    public function __construct(private RollRepositoryInterface $rollRepository, private EmployeeFetcher $employeeFetcher,
        private PrinterRepositoryInterface $printerRepository,
        private RollDataTransformer $rollDataTransformer)
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
        $rollsIds = array_unique(array_filter(array_map(fn ($roll) => $roll->getEmployeeId(), $rolls->toArray())));
        $employees = $this->employeeFetcher->getByIds($rollsIds);
        $printers = $this->printerRepository->all();

        $rollsData = [];

        foreach ($rolls as $roll) {
            $employee = $employees->filter(fn (EmployeeData $employee) => $employee->id === $roll->getEmployeeId())->first();
            /** @var bool|Printer $printer */
            $printer = $printers->filter(fn (Printer $printer) => $printer->getId() === $roll->getPrinter()?->getId())->first();

            if ($printer) {
                $printer = new PrinterData(
                    id: $printer->getId(),
                    name: $printer->name,
                    isAvailable: $printer->isAvailable(),
                );
            }

            $data = $this->rollDataTransformer->fromEntity($roll);

            $data->withEmployee($employee ?: null);
            $data->withPrinter($printer ?: null);

            $rollsData[] = $data;
        }

        return $rollsData;
    }

    /**
     * Retrieves a single roll data along with employee and printer information by its ID.
     *
     * @param string $id the ID of the roll
     *
     * @return RollData the roll data along with employee and printer information
     */
    public function getSingle(string $id): RollData
    {
        $roll = $this->rollRepository->findById($id);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        $data = $this->rollDataTransformer->fromEntity($roll);

        if ($roll->getPrinter()?->getId()) {
            $printer = $this->printerRepository->findById($roll->getPrinter()?->getId());

            $data->withPrinter(new PrinterData(
                id: $printer->getId(),
                name: $printer->name,
                isAvailable: $printer->isAvailable(),
            ));
        }

        if ($roll->getEmployeeId()) {
            $employee = $this->employeeFetcher->getById($roll->getEmployeeId());
            $data->withEmployee($employee);
        }

        return $data;
    }
}
