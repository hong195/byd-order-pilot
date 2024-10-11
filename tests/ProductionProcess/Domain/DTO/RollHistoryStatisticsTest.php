<?php

/**
 * Test case for the RollHistoryStatistics DTO.
 */

namespace App\Tests\ProductionProcess\Domain\DTO;

use App\ProductionProcess\Domain\DTO\RollHistoryStatistics;
use App\ProductionProcess\Domain\ValueObject\Process;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class RollHistoryStatisticsTest extends TestCase
{
    /**
     *
     */
    public function test_create(): void
    {
        $rollId = 1;
        $process = Process::from('order_check_in');
        $type = 'employee-assigned';
        $happenedAt = new \DateTimeImmutable('2024-10-04 10:27:26');

        $statistics = new RollHistoryStatistics($rollId, $process, $type, $happenedAt);

        $this->assertEquals(1, $statistics->getRollId());
        $this->assertEquals('order_check_in', $statistics->getProcess());
        $this->assertEquals('employee-assigned', $statistics->getType());
        $this->assertEquals('2024-10-04 10:27:26', $statistics->getHappenedAt());
    }
}
