<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Doctrine;

use DASPRiD\LocalDateTime\LocalTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TimeType;
use InvalidArgumentException;

final class LocalTimeType extends TimeType
{
    public const NAME = 'localtime';

    public function getName()
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof LocalTime) {
            return $value->format($platform->getTimeFormatString());
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', LocalTime::class]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof LocalTime) {
            return $value;
        }

        try {
            return LocalTime::createFromFormat($platform->getTimeFormatString(), $value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getTimeFormatString()
            );
        }
    }
}
