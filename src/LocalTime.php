<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use DASPRiD\LocalDateTime\Temporal\ComparisionTrait;
use DASPRiD\LocalDateTime\Temporal\FormatTrait;
use DASPRiD\LocalDateTime\Temporal\ModificationTrait;
use DASPRiD\LocalDateTime\Temporal\TemporalInterface;
use DASPRiD\LocalDateTime\Temporal\TimeGetterTrait;
use Serializable;

final class LocalTime implements TemporalInterface, Serializable
{
    use ComparisionTrait;
    use FormatTrait;
    use ModificationTrait;
    use TimeGetterTrait;

    /**
     * @var DateTimeImmutable
     */
    private $dateTime;

    protected function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime->setDate(1970, 1, 1);
    }

    public function serialize() : string
    {
        return serialize($this->dateTime->format('H:i:s'));
    }

    public function unserialize($serialized) : void
    {
        $this->dateTime = self::createFromFormat('H:i:s', unserialize($serialized))->getInternalDateTime();
    }

    /**
     * Creates a new LocalTime from individual units.
     */
    public static function create(int $hour, int $minute, int $second) : self
    {
        return self::createFromFormat('H:i:s', sprintf('%02d:%02d:%02d', $hour, $minute, $second));
    }

    /**
     * Creates a new LocalTime from the current system time.
     */
    public static function createFromNow(?DateTimeZone $timeZone = null) : self
    {
        return self::createFromFormat('H:i:s', (new DateTimeImmutable('now', $timeZone))->format('H:i:s'));
    }

    /**
     * Creates a new LocalTime from a DateTime object.
     */
    public static function createFromDateTime(DateTimeInterface $dateTime) : self
    {
        return self::createFromFormat('H:i:s', $dateTime->format('H:i:s'));
    }

    public function getInternalDateTime() : DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function __toString() : string
    {
        return $this->dateTime->format('H:i:s');
    }

    protected static function getAllowedFormatCharacters() : string
    {
        return 'aAghGHisu\\t ;:/.,\\-()?*!|+';
    }

    protected static function getEscapedOutputCharacters() : string
    {
        return 'crUeIOPTZLoYyFmMntWdDjlNSwz';
    }
}
