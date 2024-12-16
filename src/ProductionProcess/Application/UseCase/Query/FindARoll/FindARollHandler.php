<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindARoll;

use App\ProductionProcess\Application\Service\Roll\RollListService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindARollHandler implements QueryHandlerInterface
{
    /**
     * Class constructor.
     *
     */
    public function __construct(private RollListService $rollListService)
    {
    }

    /**
     * Invokes the FindARollQuery and returns the FindARollResult.
     *
     * @param FindARollQuery $rollQuery the query used to find the roll
     *
     * @return FindARollResult the result of finding the roll
     */
    public function __invoke(FindARollQuery $rollQuery): FindARollResult
    {
        $rollData = $this->rollListService->getSingle($rollQuery->rollId);

        return new FindARollResult($rollData);
    }
}
