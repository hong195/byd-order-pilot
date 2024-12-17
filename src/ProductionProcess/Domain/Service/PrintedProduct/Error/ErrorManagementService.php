<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct\Error;

use App\ProductionProcess\Domain\Aggregate\Roll\History\History;
use App\ProductionProcess\Domain\Exceptions\RollErrorManagementException;
use App\ProductionProcess\Domain\Factory\ErrorFactory;
use App\ProductionProcess\Domain\Repository\PrintedProduct\Error\ErrorRepositoryInterface;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollHistory\HistoryRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ErrorManagementService class handles error recording.
 */
final readonly class ErrorManagementService implements ErrorManagementServiceInterface
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
     * Records an error for a specific printed product and process.
     *
     * @param int         $printedProductId The ID of the printed product
     * @param Process     $process          The process related to the error
     * @param string      $noticerId        The employee ID who noticed the error
     * @param string|null $reason           The reason for the error (optional)
     *
     * @throws NotFoundHttpException        If the roll for the printed product does not exist
     * @throws RollErrorManagementException
     */
    public function recordError(string $printedProductId, Process $process, string $noticerId, ?string $reason = null): void
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
     * Retrieve the responsible employee ID based on the printed product ID and process.
     *
     * @param string  $printedProductId The ID of the printed product
     * @param Process $process          The process for which the responsible employee is needed
     *
     * @return string|null The ID of the responsible employee, or null if not found
     *
     * @throws NotFoundHttpException        When the roll associated with the printed product does not exist
     * @throws RollErrorManagementException When the roll history does not contain the specified process
     */
    private function getResponsibleEmployeeId(string $printedProductId, Process $process): ?string
    {
        $printedProduct = $this->productRepository->findById($printedProductId);

        if (!$printedProduct || !$roll = $printedProduct->getRoll()) {
            throw new NotFoundHttpException('Roll does not exist');
        }

        $rollHistories = $this->historyRepository->findFullHistory($roll->getId());

        if (empty($rollHistories)) {
            RollErrorManagementException::because('Roll history does not contain the process');
        }

        $histories = array_filter($rollHistories, fn (History $history) => $history->process === $process);

        if (empty($histories)) {
            RollErrorManagementException::because('Roll history does not contain the process');
        }

        $history = array_pop($histories);

        return $history->getEmployeeId();
    }
}
