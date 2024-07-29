<?php

declare(strict_types=1);

namespace App\Rolls\Infrastructure\Service;

use App\Rolls\Application\Service\AssetUrlServiceInterface;

/**
 * Class SiteAssetUrlService.
 *
 * This class implements the AssetUrlServiceInterface and provides functionality for retrieving the link for a given file.
 */
final readonly class SiteAssetUrlService implements AssetUrlServiceInterface
{
	/**
	 * Class constructor.
	 *
	 * @param string $siteUrl The URL of the site.
	 */
	public function __construct(private string $siteUrl)
    {
    }

    /**
     * Retrieves the link for a given file.
     *
     * @param string $fileName the name of the file to create the link for
     *
     * @return string the link for the specified file
     */
    public function getLink(string $fileName, ?string $source = null): string
    {
        if (!$source || 'local' === $source) {
            return $this->siteUrl.'/'.$fileName;
        }

        return $fileName;
    }
}
