<?php

declare(strict_types = 1);

namespace Hop\Ranges;

interface RangesCalculator
{
    /**
     * @param Range ...$ranges
     * @return Range[]
     */
    public function sum(Range ...$ranges): array;

    /**
     * @param Range $minuend
     * @param Range ...$subtrahends
     * @return Range[]
     */
    public function sub(Range $minuend, Range ...$subtrahends): array;
}
