<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Temporal;

use DateTimeImmutable;

trait DateGetterTrait
{
    /**
     * Returns the year of the date.
     */
    public function getYear() : int
    {
        return (int) $this->getInternalDateTime()->format('Y');
    }

    /**
     * Returns the month of the date.
     */
    public function getMonth() : int
    {
        return (int) $this->getInternalDateTime()->format('n');
    }

    /**
     * Returns the day of the date.
     */
    public function getDay() : int
    {
        return (int) $this->getInternalDateTime()->format('j');
    }

    abstract public function getInternalDateTime() : DateTimeImmutable;
}
