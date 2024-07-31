<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate\Lamination;

use App\Orders\Domain\Aggregate\ValueObject\Quality;
use App\Shared\Domain\Aggregate\Aggregate;
use App\Shared\Domain\Service\AssertService;

final class Lamination extends Aggregate
{
    private ?int $id = null;

    private string $name;

    private \DateTimeInterface $dateAdded;

    private int $length = 0;

    private Quality $quality;

    private ?string $qualityNotes = null;
    private LaminationType $laminationType;

    private int $priority = 0;

    public function __construct(
        string $name,
        Quality $quality,
        LaminationType $laminationType,
        int $length = 0,
        ?string $qualityNotes = null,
        int $priority = 0
    ) {
        $this->name = $name;
        $this->quality = $quality;
        $this->dateAdded = new \DateTimeImmutable();
        $this->laminationType = $laminationType;

        if ($length < 0) {
            throw new \InvalidArgumentException('Length must be greater than 0');
        }

        $this->length = $length;
        $this->priority = $priority;
        $this->qualityNotes = $qualityNotes;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $name): void
    {
        AssertService::notEmpty($name, 'Name cannot be empty');
        $this->name = $name;
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

    public function updateLength(int $length): void
    {
        AssertService::notEq(0, 'Length must be greater than 0');

        $this->length = $length;
    }

    public function getLaminationType(): string
    {
        return $this->laminationType->value;
    }

    public function changeLaminationType(LaminationType $laminationType): void
    {
        $this->laminationType = $laminationType;
    }

    public function changePriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function changeQuality(Quality $quality): void
    {
        $this->quality = $quality;
    }

    public function changeQualityNotes(string $qualityNotes): void
    {
        $this->qualityNotes = $qualityNotes;
    }
}
