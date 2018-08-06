<?php

declare(strict_types = 1);

namespace Test;

use Hop\Ranges\Range;
use PHPUnit\Framework\TestCase;

final class BalanceTest extends TestCase
{
    public function test_getters(): void
    {
        $dateFrom = new \DateTime('2018-01-01 00:00:00');
        $dateTo = new \DateTime('2019-01-01 00:00:00');

        $range = new Range($dateFrom, $dateTo);

        $this->assertEquals($dateFrom, $range->dateFrom());
        $this->assertEquals($dateTo, $range->dateTo());
    }

    public function test_dateToEarly(): void
    {
        $dateFrom = new \DateTime('2018-01-01 00:00:00');
        $dateTo = new \DateTime('2019-01-01 00:00:00');

        $this->expectException(\InvalidArgumentException::class);
        new Range($dateTo, $dateFrom);
    }
}
