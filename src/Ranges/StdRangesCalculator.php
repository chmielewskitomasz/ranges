<?php

declare(strict_types = 1);

namespace Hop\Ranges;

final class StdRangesCalculator implements RangesCalculator
{
    /**
     * @inheritdoc
     */
    public function sum(Range ...$ranges): array
    {
        if (\count($ranges) === 0) {
            return [];
        }

        $this->sortRanges($ranges);
        return $this->mergeRanges($ranges);
    }

    /**
     * @param Range[] $ranges
     */
    private function sortRanges(array &$ranges): void
    {
        \usort($ranges, function (Range $range1, Range $range2): int {
            return $range1->dateFrom() <=> $range2->dateFrom();
        });
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function sub(Range $minuend, Range ...$subtrahends): array
    {
        $ranges = [$minuend];
        foreach ($subtrahends as $subtrahend) {
            $ranges = $this->removeRangeFromRanges($subtrahend, $ranges);
        }
        return $ranges;
    }

    /**
     * @param Range $range
     * @param Range[] $ranges
     * @return Range[]
     * @throws \Exception
     */
    private function removeRangeFromRanges(Range $range, array $ranges): array
    {
        $resultRanges = [];
        $interval = new \DateInterval('PT1S');
        $newRangeFrom = null;
        $newRangeTo = null;
        foreach ($ranges as $minuend) {
            if ($range->dateFrom() < $minuend->dateFrom() && $range->dateTo() > $minuend->dateTo()) {
                continue;
            }

            if ($minuend->dateTo() < $range->dateFrom() || $minuend->dateFrom() > $range->dateTo()) {
                $resultRanges[] = $minuend;
                continue;
            }

            if ($range->dateFrom() > $minuend->dateFrom()) {
                $dateTo = new \DateTime($range->dateFrom()->format('Y-m-d H:i:s'));
                $dateTo->sub($interval);
                $resultRanges[] = new Range($minuend->dateFrom(), $dateTo);
                if ($range->dateTo() < $minuend->dateTo()) {
                    $dateFrom = new \DateTime($range->dateTo()->format('Y-m-d H:i:s'));
                    $dateFrom->add($interval);
                    $resultRanges[] = new Range($dateFrom, $minuend->dateTo());
                }
                continue;
            }

            if ($range->dateTo() < $minuend->dateTo()) {
                $dateFrom = new \DateTime($range->dateTo()->format('Y-m-d H:i:s'));
                $dateFrom->add($interval);
                $resultRanges[] = new Range($dateFrom, $minuend->dateTo());
            }
        }
        return $resultRanges;
    }

    /**
     * @param Range[] $ranges
     * @return array
     */
    private function mergeRanges(array $ranges): array
    {
        $result = [];
        $startRange = null;
        $endRange = null;
        foreach ($ranges as $key => $range) {
            $nextRange = @$ranges[$key + 1];
            if ($startRange === null) {
                $startRange = $range->dateFrom();
            }

            if ($endRange === null || $range->dateTo() > $endRange) {
                $endRange = $range->dateTo();
            }

            if ($nextRange == null || $nextRange->dateFrom() > $range->dateTo()) {
                $result[] = new Range($startRange, $endRange);
                $startRange = null;
                $endRange = null;
            }
        }
        return $result;
    }
}
