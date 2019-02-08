<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Doctrine;

use DASPRiD\LocalDateTime\LocalDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateType;
use InvalidArgumentException;

final class LocalDateType extends DateType
{
    public const NAME = 'localdate';

    public function getName()
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof LocalDate) {
            return $value->format($platform->getDateFormatString());
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', LocalDate::class]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof LocalDate) {
            return $value;
        }

        try {
            return LocalDate::createFromFormat($platform->getDateFormatString(), $value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateFormatString()
            );
        }
    }
}
