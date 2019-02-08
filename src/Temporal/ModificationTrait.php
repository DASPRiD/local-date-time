<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Temporal;

use DateInterval;
use DateTimeImmutable;

trait ModificationTrait
{
    /**
     * Adds a given interval to the temporal and returns a new instance.
     */
    public function add(DateInterval $interval) : self
    {
        return new self($this->getInternalDateTime()->add($interval));
    }

    /**
     * Subtracts a given interval from the temporal and returns a new instance.
     */
    public function sub(DateInterval $interval) : self
    {
        return new self($this->getInternalDateTime()->sub($interval));
    }

    /**
     * Modifies the temporal and returns a new instance.
     *
     * @see http://php.net/manual/en/datetime.modify.php for more details.
     */
    public function modify(string $modify) : self
    {
        return new self($this->getInternalDateTime()->modify($modify));
    }

    abstract public function getInternalDateTime() : DateTimeImmutable;
    abstract protected function __construct(DateTimeImmutable $dateTime);
}
