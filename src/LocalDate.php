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
use Serializable;

final class LocalDate implements TemporalInterface, Serializable
{
    use ComparisionTrait;
    use DateGetterTrait;
    use FormatTrait;
    use ModificationTrait;

    /**
     * @var DateTimeImmutable
     */
    private $dateTime;

    protected function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime->setTime(0, 0, 0);
    }

    public function serialize() : string
    {
        return serialize($this->dateTime->format('Y-m-d'));
    }

    public function unserialize($serialized) : void
    {
        $this->dateTime = self::createFromFormat('Y-m-d', unserialize($serialized))->getInternalDateTime();
    }

    /**
     * Creates a new LocalDate from individual units.
     */
    public static function create(int $year, int $month, int $day) : self
    {
        return self::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $year, $month, $day));
    }

    /**
     * Creates a new LocalDate from the current system time.
     */
    public static function createFromNow() : self
    {
        return self::createFromFormat('Y-m-d', date('Y-m-d'));
    }

    /**
     * Creates a new LocalDate from a DateTime object.
     */
    public static function createFromDateTime(DateTimeInterface $dateTime) : self
    {
        return self::createFromFormat('Y-m-d', $dateTime->format('Y-m-d'));
    }

    public function getInternalDateTime() : DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function __toString() : string
    {
        return $this->dateTime->format('Y-m-d');
    }

    protected static function getAllowedFormatCharacters() : string
    {
        return 'djDlSzFMmnYy\\t ;:/.,\\-()?*!|+';
    }

    protected static function getEscapedOutputCharacters() : string
    {
        return 'crUeIOPTZaABgGhHisuv';
    }
}
