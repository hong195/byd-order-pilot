<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Validates a Roll object.
 */
final readonly class GeneralProcessValidation
{
	/**
	 * Validates a Roll object.
	 *
	 * @param Roll|null $roll The roll object to validate.
	 *
	 * @throws NotFoundHttpException
	 */
	public function validate(?Roll $roll): void
    {
        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        if ($roll->getJobs()->isEmpty()) {
            throw new NotFoundHttpException('No Orders found!');
        }

        if (!$roll->getEmployeeId()) {
            throw new NotFoundHttpException('No assigned employee found!');
        }
    }
}
