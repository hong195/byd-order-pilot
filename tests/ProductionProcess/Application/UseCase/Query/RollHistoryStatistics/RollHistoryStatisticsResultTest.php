<?php

/**
 * Class RollHistoryStatisticsResultTest.
 *
 * This class is responsible for testing the toArray method of the RollHistoryStatistics class.
 */

namespace App\Tests\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics;

use App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics\RollHistoryStatisticsResult;
use App\ProductionProcess\Domain\DTO\RollHistoryStatistics;
use App\ProductionProcess\Domain\ValueObject\Process;
use PHPUnit\Framework\TestCase;

/**
 * Validates the RollHistoryStatisticsResult's array conversion.
 *
 * Ensures that the RollHistoryStatisticsResult object accurately translates its internal state
 * into a structured array with expected values.
 */
class RollHistoryStatisticsResultTest extends TestCase
{
    /**
     * Test method for converting RollHistoryStatistics to an array representation.
     */
    public function test_to_array(): void
    {
        $rollId = 1;
        $process = Process::from('order_check_in');
        $type = 'employee-assigned';
        $happenedAt = new \DateTimeImmutable('2024-10-04 10:27:26');

        // When
        $statistics = new RollHistoryStatistics($rollId, $process, $type, $happenedAt);

        // Expected result
        $expectedArray = [
            'rollId' => 1,
            'process' => 'order_check_in',
            'type' => 'employee-assigned',
            'happenedAt' => '2024-10-04 10:27:26',
        ];

        $result = (new RollHistoryStatisticsResult([$statistics]))->toArray();

        // Then
        $this->assertEquals($expectedArray, $result[0]);
    }
}
