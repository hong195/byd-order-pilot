<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Aggregate\OrderStack\OrderStack;
use App\Orders\Domain\Repository\OrderStackRepositoryInterface;
use App\Orders\Domain\Repository\PrinterFilter;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;

final readonly class AssignPrinterService
{
    public function __construct(public PrinterRepositoryInterface $printerRepository, public OrderStackRepositoryInterface $orderStackRepository)
    {
    }

    public function __invoke(OrderStack $orderStack): void
    {
        $printerFilter = new PrinterFilter([$orderStack->getRollType()->value]);

        if ($orderStack->getLaminationType()) {
            $printerFilter->setLaminationType([$orderStack->getLaminationType()->value]);
        }

        $printers = $this->printerRepository->findQueried($printerFilter);

        $orderStack->assignPrinters($printers);

        $this->orderStackRepository->save($orderStack);
    }
}
