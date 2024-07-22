<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entity;

final class MediaFile
{
	/**
	 * @phpstan-ignore-next-line
	 */
	private ?int $id;
	public function __construct(
		private string $filename,
		private string $source,
		private string $path,
		private string $type,
		private int    $ownerId,
		private string $ownerType
	)
	{
	}

	public function getFilename(): string
	{
		return $this->filename;
	}

	public function setFilename(string $filename): void
	{
		$this->filename = $filename;
	}

	public function getSource(): string
	{
		return $this->source;
	}

	public function setSource(string $source): void
	{
		$this->source = $source;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function setPath(string $path): void
	{
		$this->path = $path;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function setType(string $type): void
	{
		$this->type = $type;
	}

	public function getOwnerId(): int
	{
		return $this->ownerId;
	}

	public function setOwnerId(int $ownerId): void
	{
		$this->ownerId = $ownerId;
	}

	public function getOwnerType(): string
	{
		return $this->ownerType;
	}

	public function setOwnerType(string $ownerType): void
	{
		$this->ownerType = $ownerType;
	}
}
