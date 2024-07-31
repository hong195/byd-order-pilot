<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate\Printer;

/**
 * Class Printer.
 *
 * Represents a printer.
 */
final class Printer
{
    /**
     * @phpstan-ignore-next-line
     */
    private int $id;

    /**
     * Class Constructor.
     *
     * @param string $name the name property
     */
    public function __construct(private string $name)
    {
    }

    /**
     * Get the id property.
     *
     * @return int the id property
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the name property.
     *
     * @return string the name property
     */
    public function getName(): string
    {
        return $this->name;
    }
}
