<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrinters;

use App\ProductionProcess\Application\DTO\Printer\PrinterDataTransformer;
use App\ProductionProcess\Domain\Repository\PrinterRepositoryInterface;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindPrintersHandler implements QueryHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param PrinterRepositoryInterface $printerRepository    the repository for printers
     * @param AccessControlService       $accessControlService the service for access control
     */
    public function __construct(private PrinterRepositoryInterface $printerRepository, private AccessControlService $accessControlService, private PrinterDataTransformer $printerDataTransformer)
    {
    }

    /**
     * Invokes the FindPrintersQuery.
     *
     * @param FindPrintersQuery $findPrintersQuery the query to find printers
     *
     * @return FindPrintersResult the result of finding printers
     *
     * @throws \Exception if access is denied
     */
    public function __invoke(FindPrintersQuery $findPrintersQuery): FindPrintersResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $printers = $this->printerRepository->findAll();

        $printersData = $this->printerDataTransformer->fromEntityList($printers);

        return new FindPrintersResult($printersData);
    }
}
