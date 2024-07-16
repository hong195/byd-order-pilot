<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Aggregate\Roll;

use App\Shared\Domain\Aggregate\Aggregate;

final class Roll extends Aggregate
{
    private ?int $id = null;

    private string $name;

    private \DateTimeInterface $dateAdded;

    private int $length = 0;

    private Quality $quality;

    private ?string $qualityNotes = null;

    private RollType $rollType;
    private int $priority = 0;

    public function __construct(string $name, Quality $quality, RollType $rollType)
    {
        $this->name = $name;
        $this->quality = $quality;
        $this->dateAdded = new \DateTimeImmutable();
        $this->rollType = $rollType;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDateAdded(): \DateTimeInterface
    {
        return $this->dateAdded;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getQuality(): string
    {
        return $this->quality->value;
    }

    public function getQualityNotes(): ?string
    {
        return $this->qualityNotes;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getRollType(): string
    {
        return $this->rollType->value;
    }

    public function changeRollType(RollType $rollType): void
    {
        $this->rollType = $rollType;
    }

    public function changePriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function changeQuality(Quality $quality): void
    {
        $this->quality = $quality;
    }
}
