<?php

declare(strict_types=1);

namespace App\Tests\Functional\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Service\Roll\GeneralProcessValidation;
use App\Tests\Functional\AbstractTestCase;
use App\Tests\Tools\FakerTools;
use App\Tests\Tools\FixtureTools;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GeneralProcessValidationTest extends AbstractTestCase
{
    use FakerTools;
    use FixtureTools;

    private GeneralProcessValidation $generalProcessValidation;

    public function setUp(): void
    {
        parent::setUp();

        $this->generalProcessValidation = self::getContainer()->get(GeneralProcessValidation::class);
    }

    public function test_it_throws_exception_when_roll_is_empty(): void
    {
        $roll = $this->loadRoll();

        $this->expectException(NotFoundHttpException::class);

        $this->generalProcessValidation->validate($roll);
    }

    public function test_it_throws_exception_when_no_orders_found(): void
    {
        $roll = $this->loadRoll();

        $this->assertEmpty($roll->getPrintedProducts());
        $this->expectException(NotFoundHttpException::class);

        $this->generalProcessValidation->validate($roll);
    }

    public function test_it_throws_exception_when_no_assigned_employee_found(): void
    {
        $roll = $this->loadRoll();

        $this->assertEmpty($roll->getEmployeeId());
        $this->expectException(NotFoundHttpException::class);

        $this->generalProcessValidation->validate($roll);
    }
}
