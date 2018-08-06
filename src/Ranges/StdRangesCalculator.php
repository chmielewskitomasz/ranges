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
     */
    public function sub(Range $minuend, Range ...$subtrahends): array
    {
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
