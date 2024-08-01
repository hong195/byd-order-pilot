<?php

namespace App\Orders\Application\Service;

/**
 * Interface AssetUrlServiceInterface.
 *
 * This interface defines the methods that a class must implement in order to provide asset URL functionality.
 */
interface AssetUrlServiceInterface
{
    /**
     * Returns the link of a given file name.
     *
     * @param string $fileName the name of the file
     *
     * @return string the link of the file
     */
    public function getLink(string $fileName, ?string $source = null): string;
}
