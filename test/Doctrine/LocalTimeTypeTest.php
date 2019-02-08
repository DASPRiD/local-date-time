<?php
declare(strict_types = 1);

namespace LocalDateTimeTest\Doctrine;

use DASPRiD\LocalDateTime\Doctrine\LocalTimeType;
use DASPRiD\LocalDateTime\LocalTime;
use DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

final class LocalTimeTypeTest extends TestCase
{
    /**
     * @var AbstractPlatform
     */
    private $platform;

    /**
     * @var LocalTimeType
     */
    private $type;

    public function setUp() : void
    {
        if (! Type::hasType(LocalTimeType::NAME)) {
            Type::addType(LocalTimeType::NAME, LocalTimeType::class);
        }

        $this->platform = $this->getMockForAbstractClass(AbstractPlatform::class);
        $this->type = Type::getType(LocalTimeType::NAME);
    }

    public function testNullConvertsToDatabaseValue() : void
    {
        $date = $this->type->convertToDatabaseValue(null, $this->platform);
        self::assertNull($date);
    }

    public function testDateConvertsToDatabaseValue() : void
    {
        $date = $this->type->convertToDatabaseValue(LocalTime::create(23, 0, 0), $this->platform);
        self::assertSame('23:00:00', $date);
    }

    public function testInvalidDateConversion() : void
    {
        $this->expectException(ConversionException::class);
        $this->type->convertToDatabaseValue(new DateTime(), $this->platform);
    }

    public function testDateConvertsToPhpValue() : void
    {
        $date = $this->type->convertToPHPValue('23:00:00', $this->platform);
        self::assertInstanceOf(LocalTime::class, $date);
        self::assertSame('23:00:00', (string) $date);
    }

    public function testNullConvertsToPhpValue() : void
    {
        $date = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($date);
    }

    public function testInvalidDateFormatConversion() : void
    {
        $this->expectException(ConversionException::class);
        $this->type->convertToPHPValue('abcdefg', $this->platform);
    }
}
