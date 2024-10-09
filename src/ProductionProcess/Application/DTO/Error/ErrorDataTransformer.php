<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\Error;

use App\ProductionProcess\Domain\Aggregate\Error;

/**
 * Converts an Error entity to ErrorData object.
 *
 * @param Error $error The Error entity to convert
 *
 * @return ErrorData The converted ErrorData object
 */
final readonly class ErrorDataTransformer
{
    /**
     * Converts an Error entity to ErrorData object.
     *
     * @param Error $error The Error entity to convert
     *
     * @return ErrorData The converted ErrorData object
     */
    public function fromErrorEntity(Error $error): ErrorData
    {
        return new ErrorData(
            id: $error->getId(),
            process: $error->process->value,
            reason: $error->getReason(),
            responsibleEmployee: $error->responsibleEmployeeId,
            noticer: $error->noticerId,
            createdAt: $error->getCreatedAt(),
        );
    }

    /**
     * Converts a list of errors into an array of error data.
     *
     * @param iterable $errors The list of errors to be converted
     *
     * @return array The array of error data
     */
    public function fromErrorsList(iterable $errors): array
    {
        $errorDataList = [];

        foreach ($errors as $error) {
            $errorDataList[] = $this->fromErrorEntity($error);
        }

        return $errorDataList;
    }
}
