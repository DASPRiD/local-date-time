<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Temporal;

use DateInterval;
use DateTimeImmutable;

trait ComparisionTrait
{
    /**
     * Compares two temporals and returns either -1, 0 or 1.
     */
    public function compare(self $other) : int
    {
        return $this->getInternalDateTime() <=> $other->getInternalDateTime();
    }

    /**
     * Returns true if this temporal is before the other temporal.
     */
    public function isBefore(self $other) : bool
    {
        return $this->getInternalDateTime() < $other->getInternalDateTime();
    }

    /**
     * Returns true if this temporal equals the other temporal.
     */
    public function isEqual(self $other) : bool
    {
        return $this->getInternalDateTime() == $other->getInternalDateTime();
    }

    /**
     * Returns true if this temporal is after the other temporal.
     */
    public function isAfter(self $other) : bool
    {
        return $this->getInternalDateTime() > $other->getInternalDateTime();
    }

    /**
     * Compares two temporals.
     *
     * If the $absolute flag is set to true, the outcome will always be positive.
     */
    public function diff(self $other, bool $absolute = false) : DateInterval
    {
        return $this->getInternalDateTime()->diff($other->getInternalDateTime(), $absolute);
    }

    abstract public function getInternalDateTime() : DateTimeImmutable;
}
