<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Exceptions\DifferentPrinterTypeException;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\PrinterValidator;
use App\Shared\Domain\Exception\DomainException;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Validates printed products if they printable by one printer and have the same film type.
 */
final class PrinterValidatorTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;
    private PrinterValidator $printerValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->printerValidator = self::getContainer()->get(PrinterValidator::class);
    }

    /**
     * @throws DomainException
     */
    public function test_it_can_successfully_check_printed_products_printer(): void
    {
        $collection1 = new ArrayCollection([]);

        $collection1->add($this->createPreparedProduct(filmType: 'chrome'));
        $collection1->add($this->createPreparedProduct(filmType: 'neon'));
        $collection1->add($this->createPreparedProduct(filmType: 'clear', lamination: 'holo_flakes'));

        $this->printerValidator->validate($collection1);
        $this->assertNull(null);

        $collection2 = new ArrayCollection([]);

        $collection2->add($this->createPreparedProduct(filmType: 'white'));
        $collection2->add($this->createPreparedProduct(filmType: 'white'));

        $this->printerValidator->validate($collection1);

        $this->assertNull(null);
    }

    /**
     * @throws DomainException
     */
    public function test_it_throws_exception_when_its_different_printers(): void
    {
        $collection1 = new ArrayCollection([]);

        $collection1->add($this->createPreparedProduct(filmType: 'white'));
        $collection1->add($this->createPreparedProduct(filmType: 'white', lamination: 'holo_flakes'));

        $this->expectException(DifferentPrinterTypeException::class);

        $this->printerValidator->validate($collection1);
    }
}
