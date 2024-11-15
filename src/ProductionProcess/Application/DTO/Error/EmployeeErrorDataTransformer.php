<?php

declare(strict_types=1);

/**
 * A data transformer for employee errors.
 */

namespace App\ProductionProcess\Application\DTO\Error;

/**
 * A transformer for handling employee errors.
 */
final readonly class EmployeeErrorDataTransformer
{
    /**
     * Converts from array to object array.
     *
     * @param iterable $errors
     * @return array
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
