<?php

declare(strict_types=1);

/**
 * A data transformer for employer errors.
 */

namespace App\ProductionProcess\Application\DTO\Error;

/**
 *
 */
final readonly class EmployerErrorDataTransformer
{
    /**
     * Converts from array to object array
     */
    public function fromErrorsList(iterable $errors): array
    {
        $errorDataList = [];

        foreach ($errors as $error) {
            $errorDataList[] = $error;
        }

        return $errorDataList;
    }
}
