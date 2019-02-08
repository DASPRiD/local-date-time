<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Temporal;

use DateTimeImmutable;

interface TemporalInterface
{
    /**
     * Formats the temporal in a given format.
     *
     * @see http://php.net/manual/en/function.date.php for possible format values.
     */
    public function format(string $format) : string;

    /**
     * Returns the internal DateTimeImmutable object.
     */
    public function getInternalDateTime() : DateTimeImmutable;

    /**
     * Converts the temporal to a unified string representation.
     */
    public function __toString() : string;
}
