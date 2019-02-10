<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DASPRiD\LocalDateTime\Temporal\ComparisionTrait;
use DASPRiD\LocalDateTime\Temporal\DateGetterTrait;
use DASPRiD\LocalDateTime\Temporal\FormatTrait;
use DASPRiD\LocalDateTime\Temporal\ModificationTrait;
use DASPRiD\LocalDateTime\Temporal\TemporalInterface;
use DASPRiD\LocalDateTime\Temporal\TimeGetterTrait;
use DateTimeZone;
use Serializable;

final class LocalDateTime implements TemporalInterface, Serializable
{
    use ComparisionTrait;
    use DateGetterTrait;
    use FormatTrait;
    use ModificationTrait;
    use TimeGetterTrait;

    /**
     * @var DateTimeImmutable
     */
    private $dateTime;

    protected function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function serialize() : string
    {
        return serialize($this->dateTime->format('Y-m-d\\TH:i:s'));
    }

    public function unserialize($serialized) : void
    {
        $this->dateTime = self::createFromFormat('Y-m-d\\TH:i:s', unserialize($serialized))->getInternalDateTime();
    }

    /**
     * Creates a new LocalDateTime from individual units.
     */
    public static function create(int $year, int $month, int $day, int $hour, int $minute, int $second) : self
    {
        return self::createFromFormat(
            'Y-m-d\\TH:i:s',
            sprintf('%04d-%02d-%02dT%02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second)
        );
    }

    /**
     * Creates a new LocalDateTime from the current system time.
     */
    public static function createFromNow(?DateTimeZone $timeZone = null) : self
    {
        return self::createFromFormat(
            'Y-m-d\\TH:i:s',
            (new DateTimeImmutable('now', $timeZone))->format('Y-m-d\\TH:i:s')
        );
    }

    /**
     * Creates a new LocalDateTime from a LocalDate and LocalTime.
     */
    public static function createFromLocalDateAndTime(LocalDate $date, LocalTime $time) : self
    {
        return self::createFromFormat(
            'Y-m-d\\TH:i:s',
            sprintf('%sT%s', $date->format('Y-m-d'), $time->format('H:i:s'))
        );
    }

    /**
     * Creates a new LocalDateTime from a DateTime object.
     */
    public static function createFromDateTime(DateTimeInterface $dateTime) : self
    {
        return self::createFromFormat('Y-m-d\\TH:i:s', $dateTime->format('Y-m-d\\TH:i:s'));
    }

    public function toLocalDate() : LocalDate
    {
        return LocalDate::createFromDateTime($this->dateTime);
    }

    public function toLocalTime() : LocalTime
    {
        return LocalTime::createFromDateTime($this->dateTime);
    }

    public function getInternalDateTime() : DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function __toString() : string
    {
        return $this->dateTime->format('Y-m-d\\TH:i:s');
    }

    protected static function getAllowedFormatCharacters() : string
    {
        return 'djDlSzFMmnYyaAghGHisu\\t ;:/.,\\-()?*!|+';
    }

    protected static function getEscapedOutputCharacters() : string
    {
        return 'crUeIOPTZ';
    }
}
