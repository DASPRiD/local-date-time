<?php
declare(strict_types = 1);

namespace LocalDateTimeTest;

use DASPRiD\LocalDateTime\LocalDate;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LocalDateTest extends TestCase
{
    public function testCreate() : void
    {
        self::assertSame('2018-01-01', (string) LocalDate::create(2018, 1, 1));
    }

    public function testCreateFromNow() : void
    {
        $before = date('Y-m-d');
        $date = LocalDate::createFromNow();
        $after = date('Y-m-d');

        self::assertGreaterThanOrEqual($before, (string) $date);
        self::assertLessThanOrEqual($after, (string) $date);
    }

    public function testCreateFromNowWithTimeZone() : void
    {
        $earlierDate = LocalDate::createFromNow(new DateTimeZone('Etc/GMT+12'));
        $laterDate = LocalDate::createFromNow(new DateTimeZone('Etc/GMT-12'));

        $this->assertSame((string) $earlierDate->modify('+1 day'), (string) $laterDate);
    }

    public function testCreateFromDateTime() : void
    {
        $date = LocalDate::createFromDateTime(new DateTimeImmutable('2018-01-01T01:00:00+05:00'));
        self::assertSame('2018-01-01', (string) $date);
    }

    public function testCreateFromFormat() : void
    {
        $date = LocalDate::createFromFormat('l, F j, Y', 'Monday, January 1, 2018');
        self::assertSame('2018-01-01', (string) $date);
    }

    public function testCreateFromInvalidFormat() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Format string "H:i:s" contains invalid characters.');
        LocalDate::createFromFormat('H:i:s', '2018-01-01');
    }

    public function testCreateFromNonMatchingFormat() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Input string "2018-01-01" does not match format string "l, F j, Y"');
        LocalDate::createFromFormat('l, F j, Y', '2018-01-01');
    }

    public function testFormat() : void
    {
        self::assertSame('Monday, January 1, 2018 H:i:s', LocalDate::create(2018, 1, 1)->format('l, F j, Y H:i:s'));
    }

    public function testDateGetters() : void
    {
        $date = LocalDate::create(2018, 1, 1);
        self::assertSame(2018, $date->getYear());
        self::assertSame(1, $date->getMonth());
        self::assertSame(1, $date->getDay());
    }

    public function testCompare() : void
    {
        self::assertSame(-1, LocalDate::create(2018, 1, 1)->compare(LocalDate::create(2018, 1, 2)));
        self::assertSame(0, LocalDate::create(2018, 1, 1)->compare(LocalDate::create(2018, 1, 1)));
        self::assertSame(1, LocalDate::create(2018, 1, 2)->compare(LocalDate::create(2018, 1, 1)));
    }

    public function testIsBefore() : void
    {
        self::assertTrue(LocalDate::create(2018, 1, 1)->isBefore(LocalDate::create(2018, 1, 2)));
        self::assertFalse(LocalDate::create(2018, 1, 1)->isBefore(LocalDate::create(2018, 1, 1)));
    }

    public function testIsAfter() : void
    {
        self::assertTrue(LocalDate::create(2018, 1, 2)->isAfter(LocalDate::create(2018, 1, 1)));
        self::assertFalse(LocalDate::create(2018, 1, 1)->isAfter(LocalDate::create(2018, 1, 1)));
    }

    public function testIsEqual() : void
    {
        self::assertFalse(LocalDate::create(2018, 1, 1)->isEqual(LocalDate::create(2018, 1, 2)));
        self::assertTrue(LocalDate::create(2018, 1, 1)->isEqual(LocalDate::create(2018, 1, 1)));
        self::assertFalse(LocalDate::create(2018, 1, 2)->isEqual(LocalDate::create(2018, 1, 1)));
    }

    public function testDiff() : void
    {
        $diff = LocalDate::create(2018, 1, 1)->diff(LocalDate::create(2018, 1, 2));
        $this->assertSame(1, $diff->days);
    }

    public function modifyProvider() : array
    {
        return [
            ['+1 day', '2018-01-02'],
            ['+23 hours', '2018-01-01'],
            ['+24 hours', '2018-01-02'],
            ['-1 second', '2017-12-31'],
            ['-1 day', '2017-12-31'],
        ];
    }

    /**
     * @dataProvider modifyProvider
     */
    public function testModify(string $modification, string $expected) : void
    {
        self::assertSame($expected, (string) LocalDate::create(2018, 1, 1)->modify($modification));
    }

    public function addProvider() : array
    {
        return [
            [new DateInterval('P1D'), '2018-01-02'],
            [new DateInterval('PT23H'), '2018-01-01'],
            [new DateInterval('PT24H'), '2018-01-02'],
        ];
    }

    /**
     * @dataProvider addProvider
     */
    public function testAdd(DateInterval $interval, string $expected) : void
    {
        self::assertSame($expected, (string) LocalDate::create(2018, 1, 1)->add($interval));
    }

    public function subProvider() : array
    {
        return [
            [new DateInterval('P1D'), '2017-12-31'],
            [new DateInterval('PT24H'), '2017-12-31'],
            [new DateInterval('PT25H'), '2017-12-30'],
        ];
    }

    /**
     * @dataProvider subProvider
     */
    public function testSub(DateInterval $interval, string $expected) : void
    {
        self::assertSame($expected, (string) LocalDate::create(2018, 1, 1)->sub($interval));
    }

    public function testSerialize() : void
    {
        $date = LocalDate::create(2018, 1, 1);
        $serialized = serialize($date);
        self::assertSame('C:31:"DASPRiD\LocalDateTime\LocalDate":18:{s:10:"2018-01-01";}', $serialized);

        $newDate = unserialize($serialized);
        self::assertSame('2018-01-01', (string) $newDate);
    }
}
