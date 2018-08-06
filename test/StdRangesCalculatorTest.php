<?php

declare(strict_types = 1);

namespace Test;

use Hop\Ranges\Range;
use Hop\Ranges\RangesCalculator;
use Hop\Ranges\StdRangesCalculator;
use PHPUnit\Framework\TestCase;

final class StdRangesCalculatorTest extends TestCase
{
    /**
     * @var StdRangesCalculator
     */
    private $calculator;

    public function setUp()
    {
        $this->calculator = new StdRangesCalculator();
    }

    public function test_instanceOf(): void
    {
        $this->assertInstanceOf(RangesCalculator::class, $this->calculator);
    }

    public function test_sumEmpty(): void
    {
        $this->assertEquals([], $this->calculator->sum());
    }

    public function test_sumNotMerge(): void
    {
        $ranges = $this->calculator->sum(
            new Range(new \DateTime('2018-01-01 00:01:01'), new \DateTime('2018-01-01 00:02:00')),
            new Range(new \DateTime('2018-02-01 00:00:00'), new \DateTime('2018-02-01 00:01:00')),
            new Range(new \DateTime('2018-01-01 00:00:00'), new \DateTime('2018-01-01 00:01:00'))
        );

        $this->assertCount(3, $ranges);

        $this->assertEquals('2018-01-01 00:00:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-01 00:01:00', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-01-01 00:01:01', $ranges[1]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-01 00:02:00', $ranges[1]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-02-01 00:00:00', $ranges[2]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-02-01 00:01:00', $ranges[2]->dateTo()->format('Y-m-d H:i:s'));
    }

    public function test_sumAndMerge(): void
    {
        $ranges = $this->calculator->sum(
            new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-01-01 00:02:00')),
            new Range(new \DateTime('2018-02-01 00:00:00'), new \DateTime('2018-02-01 00:01:00')),
            new Range(new \DateTime('2018-01-01 00:00:00'), new \DateTime('2018-01-01 00:01:00'))
        );

        $this->assertCount(2, $ranges);

        $this->assertEquals('2018-01-01 00:00:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-01 00:02:00', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-02-01 00:00:00', $ranges[1]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-02-01 00:01:00', $ranges[1]->dateTo()->format('Y-m-d H:i:s'));


        $ranges = $this->calculator->sum(
            new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-01-01 00:02:00')),
            new Range(new \DateTime('2018-01-01 00:01:58'), new \DateTime('2018-02-01 00:01:00')),
            new Range(new \DateTime('2018-01-01 00:00:00'), new \DateTime('2018-01-01 00:01:00'))
        );

        $this->assertCount(1, $ranges);

        $this->assertEquals('2018-01-01 00:00:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-02-01 00:01:00', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));


        $ranges = $this->calculator->sum(
            new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-01-01 00:02:00')),
            new Range(new \DateTime('2018-01-01 00:01:56'), new \DateTime('2018-01-01 00:01:58')),
            new Range(new \DateTime('2018-01-01 00:00:00'), new \DateTime('2018-01-01 00:01:00'))
        );

        $this->assertCount(1, $ranges);

        $this->assertEquals('2018-01-01 00:00:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-01 00:02:00', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));
    }

    public function test_sumMergeInner(): void
    {
        $ranges = $this->calculator->sum(
            new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-01-01 00:02:00')),
            new Range(new \DateTime('2018-01-01 00:01:56'), new \DateTime('2018-01-01 00:01:59')),
            new Range(new \DateTime('2018-01-01 00:01:57'), new \DateTime('2018-01-01 00:01:58')),
            new Range(new \DateTime('2018-01-01 00:00:00'), new \DateTime('2018-01-01 00:01:00'))
        );

        $this->assertCount(1, $ranges);

        $this->assertEquals('2018-01-01 00:00:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-01 00:02:00', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));
    }
}
