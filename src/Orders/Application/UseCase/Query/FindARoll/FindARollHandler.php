<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindARoll;

use App\Orders\Application\Service\Roll\RollListService;
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
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private AccessControlService $accessControlService, private RollListService $rollListService)
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
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $rollData = $this->rollListService->getSingle($rollQuery->rollId);

        return new FindARollResult($rollData);
    }
}
