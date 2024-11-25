<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Exceptions\DifferentFilmTypeException;
use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\FilmTypeValidator;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Validates printed products if they printable by one printer and have the same film type.
 */
final class FilmTypeValidatorTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private FilmTypeValidator $filmTypeValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filmTypeValidator = self::getContainer()->get(FilmTypeValidator::class);
    }

    /**
     * @throws ManualArrangeException
     */
    public function test_it_can_successfully_check_printed_products_film_type(): void
    {
        $collection = new ArrayCollection([]);

        $randomFilmType = $this->getFaker()->word();

        for ($i = 0; $i < $this->getFaker()->randomDigit() + 1; ++$i) {
            $collection->add($this->createPreparedProduct(filmType: $randomFilmType));
        }

        $this->filmTypeValidator->validate($collection);

        $this->assertNull(null);
    }

    /**
     * @throws ManualArrangeException
     */
    public function test_throws_exception_when_film_types_are_different(): void
    {
        $collection = new ArrayCollection([]);

        $collection->add($this->createPreparedProduct(filmType: $this->getFaker()->word()));
        $collection->add($this->createPreparedProduct(filmType: $this->getFaker()->word()));
        $collection->add($this->createPreparedProduct(filmType: $this->getFaker()->word()));

        $this->expectException(DifferentFilmTypeException::class);

        $this->filmTypeValidator->validate($collection);
    }
}
