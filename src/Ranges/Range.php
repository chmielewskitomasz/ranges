<?php

declare(strict_types = 1);

namespace Hop\Ranges;

final class Range
{
    /**
     * @var \DateTime
     */
    private $dateFrom;

    /**
     * @var \DateTime
     */
    private $dateTo;

    /**
     * Range constructor.
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     */
    public function __construct(
        \DateTime $dateFrom,
        \DateTime $dateTo
    ) {
        if ($dateTo < $dateFrom) {
            throw new \InvalidArgumentException('Date to must be greater than date from');
        }

        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    /**
     * @return \DateTime
     */
    public function dateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    /**
     * @return \DateTime
     */
    public function dateTo(): \DateTime
    {
        return $this->dateTo;
    }
}
