<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Doctrine;

use DASPRiD\LocalDateTime\LocalDateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TimeType;
use InvalidArgumentException;

final class LocalDateTimeType extends TimeType
{
    public const NAME = 'localdatetime';

    public function getName()
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof LocalDateTime) {
            return $value->format($platform->getDateTimeFormatString());
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', LocalDateTime::class]
        );
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof LocalDateTime) {
            return $value;
        }

        try {
            return LocalDateTime::createFromFormat($platform->getDateTimeFormatString(), $value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }
    }
}
