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

    public function test_subNoEffect(): void
    {
        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-01-01 00:02:00'));
        $range2 = new Range(new \DateTime('2018-03-01 00:01:00'), new \DateTime('2018-03-01 00:02:00'));

        $ranges = $this->calculator->sub($range1, $range2);
        $this->assertCount(1, $ranges);
        $this->assertEquals($range1->dateFrom()->format('Y-m-d H:i:s'), $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals($range1->dateTo()->format('Y-m-d H:i:s'), $ranges[0]->dateTo()->format('Y-m-d H:i:s'));
    }

    public function test_subInner(): void
    {
        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-02-10 00:01:00'));
        $range2 = new Range(new \DateTime('2018-01-10 00:01:00'), new \DateTime('2018-02-03 00:02:00'));

        $ranges = $this->calculator->sub($range1, $range2);
        $this->assertCount(2, $ranges);

        $this->assertEquals('2018-01-01 00:01:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-10 00:00:59', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-02-03 00:02:01', $ranges[1]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-02-10 00:01:00', $ranges[1]->dateTo()->format('Y-m-d H:i:s'));


        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-02-10 00:01:00'));
        $range2 = new Range(new \DateTime('2018-01-10 00:01:00'), new \DateTime('2018-02-03 00:02:00'));
        $range3 = new Range(new \DateTime('2018-01-11 00:01:00'), new \DateTime('2018-02-01 00:02:00'));

        $ranges = $this->calculator->sub($range1, $range2, $range3);
        $this->assertCount(2, $ranges);

        $this->assertEquals('2018-01-01 00:01:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-10 00:00:59', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-02-03 00:02:01', $ranges[1]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-02-10 00:01:00', $ranges[1]->dateTo()->format('Y-m-d H:i:s'));


        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-02-10 00:01:00'));
        $range2 = new Range(new \DateTime('2018-01-10 00:01:00'), new \DateTime('2018-02-03 00:02:00'));
        $range3 = new Range(new \DateTime('2018-01-11 00:01:00'), new \DateTime('2018-02-04 00:02:00'));

        $ranges = $this->calculator->sub($range1, $range2, $range3);
        $this->assertCount(2, $ranges);

        $this->assertEquals('2018-01-01 00:01:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-10 00:00:59', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-02-04 00:02:01', $ranges[1]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-02-10 00:01:00', $ranges[1]->dateTo()->format('Y-m-d H:i:s'));


        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-02-10 00:01:00'));
        $range2 = new Range(new \DateTime('2018-01-10 00:01:00'), new \DateTime('2018-02-03 00:02:00'));
        $range3 = new Range(new \DateTime('2018-01-09 00:01:00'), new \DateTime('2018-02-04 00:02:00'));

        $ranges = $this->calculator->sub($range1, $range2, $range3);
        $this->assertCount(2, $ranges);

        $this->assertEquals('2018-01-01 00:01:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-09 00:00:59', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-02-04 00:02:01', $ranges[1]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-02-10 00:01:00', $ranges[1]->dateTo()->format('Y-m-d H:i:s'));


        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-02-10 00:01:00'));
        $range2 = new Range(new \DateTime('2018-01-10 00:01:00'), new \DateTime('2018-02-03 00:02:00'));
        $range3 = new Range(new \DateTime('2018-02-04 00:01:00'), new \DateTime('2018-02-05 00:02:00'));

        $ranges = $this->calculator->sub($range1, $range2, $range3);
        $this->assertCount(3, $ranges);

        $this->assertEquals('2018-01-01 00:01:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-10 00:00:59', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-02-03 00:02:01', $ranges[1]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-02-04 00:00:59', $ranges[1]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-02-05 00:02:01', $ranges[2]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-02-10 00:01:00', $ranges[2]->dateTo()->format('Y-m-d H:i:s'));
    }

    public function test_subLeftSide(): void
    {
        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-01-04 00:02:00'));
        $range2 = new Range(new \DateTime('2017-12-30 00:00:00'), new \DateTime('2018-01-03 00:02:00'));

        $ranges = $this->calculator->sub($range1, $range2);
        $this->assertCount(1, $ranges);
        $this->assertEquals('2018-01-03 00:02:01', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-04 00:02:00', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));
    }

    public function test_subRightSide(): void
    {
        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-01-04 00:02:00'));
        $range2 = new Range(new \DateTime('2018-01-02 00:00:00'), new \DateTime('2018-03-01 00:02:00'));

        $ranges = $this->calculator->sub($range1, $range2);
        $this->assertCount(1, $ranges);
        $this->assertEquals('2018-01-01 00:01:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-01 23:59:59', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));
    }

    public function test_subMix(): void
    {
        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-01-04 00:02:00'));
        $range2 = new Range(new \DateTime('2018-01-02 00:00:01'), new \DateTime('2018-01-02 00:01:00'));
        $range3 = new Range(new \DateTime('2018-01-03 01:00:00'), new \DateTime('2018-02-01 00:00:00'));

        $ranges = $this->calculator->sub($range1, $range2, $range3);
        $this->assertCount(2, $ranges);
        $this->assertEquals('2018-01-01 00:01:00', $ranges[0]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-02 00:00:00', $ranges[0]->dateTo()->format('Y-m-d H:i:s'));

        $this->assertEquals('2018-01-02 00:01:01', $ranges[1]->dateFrom()->format('Y-m-d H:i:s'));
        $this->assertEquals('2018-01-03 00:59:59', $ranges[1]->dateTo()->format('Y-m-d H:i:s'));
    }

    public function test_subAll(): void
    {
        $range1 = new Range(new \DateTime('2018-01-01 00:01:00'), new \DateTime('2018-01-04 00:02:00'));
        $range2 = new Range(new \DateTime('2017-12-30 00:00:00'), new \DateTime('2018-03-01 00:02:00'));

        $ranges = $this->calculator->sub($range1, $range2);
        $this->assertCount(0, $ranges);
    }
}
