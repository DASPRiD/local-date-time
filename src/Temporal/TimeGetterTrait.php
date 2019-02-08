<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Temporal;

use DateTimeImmutable;

trait TimeGetterTrait
{
    /**
     * Returns the hour of the time.
     */
    public function getHour() : int
    {
        return (int) $this->getInternalDateTime()->format('G');
    }

    /**
     * Returns the minute of the time.
     */
    public function getMinute() : int
    {
        return (int) $this->getInternalDateTime()->format('i');
    }

    /**
     * Returns the second of the time.
     */
    public function getSecond() : int
    {
        return (int) $this->getInternalDateTime()->format('s');
    }

    abstract public function getInternalDateTime() : DateTimeImmutable;
}
