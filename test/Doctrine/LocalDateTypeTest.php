<?php
declare(strict_types = 1);

namespace LocalDateTimeTest\Doctrine;

use DASPRiD\LocalDateTime\Doctrine\LocalDateType;
use DASPRiD\LocalDateTime\LocalDate;
use DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

final class LocalDateTypeTest extends TestCase
{
    /**
     * @var AbstractPlatform
     */
    private $platform;

    /**
     * @var LocalDateType
     */
    private $type;

    public function setUp() : void
    {
        if (! Type::hasType(LocalDateType::NAME)) {
            Type::addType(LocalDateType::NAME, LocalDateType::class);
        }

        $this->platform = $this->getMockForAbstractClass(AbstractPlatform::class);
        $this->type = Type::getType(LocalDateType::NAME);
    }

    public function testNullConvertsToDatabaseValue() : void
    {
        $date = $this->type->convertToDatabaseValue(null, $this->platform);
        self::assertNull($date);
    }

    public function testDateConvertsToDatabaseValue() : void
    {
        $date = $this->type->convertToDatabaseValue(LocalDate::create(1986, 1, 25), $this->platform);
        self::assertSame('1986-01-25', $date);
    }

    public function testInvalidDateConversion() : void
    {
        $this->expectException(ConversionException::class);
        $this->type->convertToDatabaseValue(new DateTime(), $this->platform);
    }

    public function testDateConvertsToPhpValue() : void
    {
        $date = $this->type->convertToPHPValue('1986-01-25', $this->platform);
        self::assertInstanceOf(LocalDate::class, $date);
        self::assertSame('1986-01-25', (string) $date);
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
