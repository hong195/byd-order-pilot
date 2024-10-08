<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct\Error;

use App\ProductionProcess\Domain\Aggregate\Roll\History\History;
use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use App\ProductionProcess\Domain\Exceptions\RollErrorManagementException;
use App\ProductionProcess\Domain\Factory\ErrorFactory;
use App\ProductionProcess\Domain\Repository\ErrorRepositoryInterface;
use App\ProductionProcess\Domain\Repository\HistoryRepositoryInterface;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ErrorManagementService class handles error recording.
 */
final readonly class ErrorManagementService
{
    /**
     * Class constructor.
     *
     * @param HistoryRepositoryInterface        $historyRepository The repository for managing history records
     * @param PrintedProductRepositoryInterface $productRepository The repository for managing printed products
     * @param ErrorRepositoryInterface          $errorRepository   The repository for managing error records
     * @param ErrorFactory                      $errorFactory      The factory for creating error instances
     */
    public function __construct(private HistoryRepositoryInterface $historyRepository, private PrintedProductRepositoryInterface $productRepository, private ErrorRepositoryInterface $errorRepository, private ErrorFactory $errorFactory)
    {
    }

    /**
     * Records an error for a specific printed product and process, assigning it to the responsible employee.
     *
     * @param int     $printedProductId The ID of the printed product
     * @param Process $process          The process associated with the error
     * @param int     $noticerId        The ID of the employee who noticed the error
     * @param string  $reason           (Optional) The reason or details of the error
     *
     * @throws NotFoundHttpException        If the roll for the printed product does not exist
     * @throws RollErrorManagementException If there are issues with roll history or process data
     */
    public function recordError(int $printedProductId, Process $process, int $noticerId, ?string $reason = null): void
    {
        $employeeId = $this->getResponsibleEmployeeId($printedProductId, $process);

        $errorFactory = $this->errorFactory->make(
            printedProductId: $printedProductId,
            process: $process,
            responsibleEmployeeId: $employeeId,
            noticerId: $noticerId
        );

        if ($reason) {
            $errorFactory->withReason($reason);
        }

        $error = $errorFactory->build();

        $this->errorRepository->add($error);
    }

    /**
     * Retrieves the responsible employee ID for a specific printed product and process.
     *
     * @param int     $printedProductId The ID of the printed product
     * @param Process $process          The process to check for
     *
     * @return int|null The ID of the responsible employee or null if not found
     *
     * @throws NotFoundHttpException        If the roll for the printed product does not exist
     * @throws RollErrorManagementException
     */
    private function getResponsibleEmployeeId(int $printedProductId, Process $process): ?int
    {
        $printedProduct = $this->productRepository->findById($printedProductId);

        if (!$printedProduct || !$printedProduct->getRoll()) {
            throw new NotFoundHttpException('Roll does not exist');
        }

        $rollId = $printedProduct->getRoll()->getId();
        $rollHistories = $this->historyRepository->findByRollId($rollId);

        if (empty($rollHistories)) {
            throw new RollErrorManagementException('Roll history does not contain the process');
        }

        usort($rollHistories, fn (History $a, History $b) => $a->happenedAt <=> $b->happenedAt);

        $histories = array_filter($rollHistories, fn (History $history) => $history->process === $process && Type::PROCESS_CHANGED === $history->type);

        if (empty($histories)) {
            RollErrorManagementException::because('Roll history does not contain the process');
        }

        $history = array_pop($histories);

        return $history->getEmployeeId();
    }
}
