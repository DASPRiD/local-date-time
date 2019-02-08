<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime;

use IntlDateFormatter;
use DASPRiD\LocalDateTime\Temporal\TemporalInterface;
use DASPRiD\LocalDateTime\Temporal\UtcTimeZone;

final class IntlLocalDateFormatter
{
    /**
     * @var IntlDateFormatter
     */
    private $intlDateFormatter;

    /**
     * Creates a new IntlLocalDateFormatter.
     *
     * @see http://php.net/manual/en/intldateformatter.create.php for details.
     */
    public function __construct(string $locale, int $dateType, int $timeType)
    {
        $this->intlDateFormatter = new IntlDateFormatter($locale, $dateType, $timeType, UtcTimeZone::instance());
    }

    public static function dateFormatter(string $locale, int $dateType) : self
    {
        return new self($locale, $dateType, IntlDateFormatter::NONE);
    }

    public static function timeFormatter(string $locale, int $timeType) : self
    {
        return new self($locale, IntlDateFormatter::NONE, $timeType);
    }

    /**
     * Formats the temporal.
     */
    public function format(TemporalInterface $temporal) : string
    {
        return $this->intlDateFormatter->format($temporal->getInternalDateTime());
    }
}
