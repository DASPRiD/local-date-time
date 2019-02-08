<?php
declare(strict_types = 1);

namespace LocalDateTimeTest\Doctrine;

use DASPRiD\LocalDateTime\Doctrine\LocalDateTimeType;
use DASPRiD\LocalDateTime\LocalDateTime;
use DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

final class LocalDateTimeTypeTest extends TestCase
{
    /**
     * @var AbstractPlatform
     */
    private $platform;

    /**
     * @var LocalDateTimeType
     */
    private $type;

    public function setUp() : void
    {
        if (! Type::hasType(LocalDateTimeType::NAME)) {
            Type::addType(LocalDateTimeType::NAME, LocalDateTimeType::class);
        }

        $this->platform = $this->getMockForAbstractClass(AbstractPlatform::class);
        $this->type = Type::getType(LocalDateTimeType::NAME);
    }

    public function testNullConvertsToDatabaseValue() : void
    {
        $date = $this->type->convertToDatabaseValue(null, $this->platform);
        self::assertNull($date);
    }

    public function testDateConvertsToDatabaseValue() : void
    {
        $date = $this->type->convertToDatabaseValue(LocalDateTime::create(1986, 1, 25, 23, 0, 0), $this->platform);
        self::assertSame('1986-01-25 23:00:00', $date);
    }

    public function testInvalidDateConversion() : void
    {
        $this->expectException(ConversionException::class);
        $this->type->convertToDatabaseValue(new DateTime(), $this->platform);
    }

    public function testDateConvertsToPhpValue() : void
    {
        $date = $this->type->convertToPHPValue('1986-01-25 23:00:00', $this->platform);
        self::assertInstanceOf(LocalDateTime::class, $date);
        self::assertSame('1986-01-25T23:00:00', (string) $date);
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
