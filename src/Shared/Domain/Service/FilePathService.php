<?php

declare(strict_types=1);

namespace App\Shared\Domain\Service;

final readonly class FilePathService
{
    /**
     * Generates the path for a file upload.
     *
     * This method takes a file name and extension as input and generates a unique path for the file upload.
     * The path is composed of the "/uploads/" directory, followed by the file name, a randomly generated unique ID,
     * and finally the extension of the file.
     *
     * @param string $fileName the name of the file
     *
     * @return string the generated path for the file upload
     */
    public function generatePath(string $fileName): string
    {
        return 'uploads/'.uniqid().'_'.$fileName;
    }
}
