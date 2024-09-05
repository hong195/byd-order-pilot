<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Roll;

use App\Orders\Domain\Aggregate\Roll;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class GeneralProcessValidation
{
    public function __construct()
    {
    }

    public function validate(?Roll $roll): void
    {
        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        if ($roll->getOrders()->isEmpty()) {
            throw new NotFoundHttpException('No Orders found!');
        }

        if (!$roll->getEmployeeId()) {
            throw new NotFoundHttpException('No assigned employee found!');
        }
    }
}
