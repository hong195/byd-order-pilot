<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * OrderStackData represents the data for an order stack.
 *
 * This class is responsible for storing and providing access to the data related to an order stack.
 * It contains properties to store information such as the ID, name, length, roll type, date added, date updated, and lamination type of the order stack.
 * The properties are declared as public, allowing for direct access and assignment.
 * The class also provides a constructor method to create a new instance of the class with the given data.
 *
 * Example usage:
 * $orderStack = new OrderStackData(1, "Sample Stack", 100, "Roll Type", new \DateTime(), new \DateTime(), "Lamination Type");
 */
final readonly class OrderStackData
{
    /**
     * Constructor method for creating a new instance of the class.
     *
     * @param int                $id             the ID of the object
     * @param string             $name           the name of the object
     * @param string             $priority       the priority of the object
     * @param int                $length         the length of the object
     * @param string             $rollType       the roll type of the object
     * @param \DateTimeInterface $dateAdded      the date when the object was added
     * @param \DateTimeInterface $updatedAt      the date when the object was last updated
     * @param string|null        $laminationType the lamination type of the object (nullable)
     */
    public function __construct(
        public int $id,
        public string $name,
        public int $length,
        public string $priority,
        public string $rollType,
        public \DateTimeInterface $dateAdded,
        public \DateTimeInterface $updatedAt,
        public ?string $laminationType,
    ) {
    }
}
