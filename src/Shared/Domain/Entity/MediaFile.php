<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entity;

use App\Shared\Domain\Service\UlidService;

/**
 * Represents a media file.
 */
class MediaFile
{
    /**
     * @phpstan-ignore-next-line
     */
    private string $id;

    /**
     * @param string      $filename  The filename
     * @param string      $source    The source
     * @param string      $path      The path
     * @param string|null $type      The type (optional)
     * @param string|null $ownerId   The owner ID (optional)
     * @param string|null $ownerType The owner type (optional)
     */
    public function __construct(
        private string $filename,
        private string $source,
        private string $path,
        private ?string $type = null,
        private ?string $ownerId = null,
        private ?string $ownerType = null,
    ) {
		$this->id = UlidService::generate();
    }

    /**
     * Gets the filename.
     *
     * @return string the filename
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Get the unique identifier for the object.
     *
     * @return int the unique identifier for the object
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the filename.
     *
     * @param string $filename the filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * Returns the source.
     *
     * @return string the source
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Sets the source.
     *
     * @param string $source the source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    /**
     * Gets the path.
     *
     * @return string the path
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets the path.
     *
     * @param string $path the path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Gets the type.
     *
     * @return string the type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of the object.
     *
     * @param string $type the type of the object
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Gets the owner ID.
     *
     * @return int|null the owner ID, or null if not set
     */
    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    /**
     * Sets the owner ID.
     *
     * @param string $ownerId the owner ID to set
     */
    public function setOwnerId(string $ownerId): void
    {
        $this->ownerId = $ownerId;
    }

    /**
     * Gets the owner type.
     *
     * @return ?string the owner type
     */
    public function getOwnerType(): ?string
    {
        return $this->ownerType;
    }

    /**
     * Sets the owner type.
     *
     * @param string $ownerType the owner type to set
     */
    public function setOwnerType(string $ownerType): void
    {
        $this->ownerType = $ownerType;
    }
}
