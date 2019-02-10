<?php
declare(strict_types = 1);

namespace LocalDateTimeTest;

use DASPRiD\LocalDateTime\LocalTime;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LocalTimeTest extends TestCase
{
    public function testCreate() : void
    {
        self::assertSame('23:00:00', (string) LocalTime::create(23, 0, 0));
    }

    public function testCreateFromNow() : void
    {
        $before = date('H:i:s');
        $time = LocalTime::createFromNow();
        $after = date('H:i:s');

        self::assertGreaterThanOrEqual($before, (string) $time);
        self::assertLessThanOrEqual($after, (string) $time);
    }

    public function testCreateFromNowWithTimeZone() : void
    {
        $earlierTime = LocalTime::createFromNow(new DateTimeZone('Etc/GMT+12'));
        $laterTime = LocalTime::createFromNow(new DateTimeZone('Etc/GMT+11'));

        $this->assertSame((string) $earlierTime->modify('+1 hour'), (string) $laterTime);
    }

    public function testCreateFromDateTime() : void
    {
        $time = LocalTime::createFromDateTime(new DateTimeImmutable('2018-01-01T01:00:00+05:00'));
        self::assertSame('01:00:00', (string) $time);
    }

    public function testCreateFromFormat() : void
    {
        $time = LocalTime::createFromFormat('s:i:H', '00:00:23');
        self::assertSame('23:00:00', (string) $time);
    }

    public function testCreateFromInvalidFormat() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Format string "Y-m-d" contains invalid characters.');
        LocalTime::createFromFormat('Y-m-d', '23::00:00');
    }

    public function testCreateFromNonMatchingFormat() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Input string "23:00:00" does not match format string "s-i-H"');
        LocalTime::createFromFormat('s-i-H', '23:00:00');
    }

    public function testFormat() : void
    {
        self::assertSame('l, F j, Y 23:00:00', LocalTime::create(23, 0, 0)->format('l, F j, Y H:i:s'));
    }

    public function testDateGetters() : void
    {
        $time = LocalTime::create(23, 0, 0);
        self::assertSame(23, $time->getHour());
        self::assertSame(0, $time->getMinute());
        self::assertSame(0, $time->getSecond());
    }

    public function testCompare() : void
    {
        self::assertSame(-1, LocalTime::create(23, 0, 0)->compare(LocalTime::create(23, 0, 1)));
        self::assertSame(0, LocalTime::create(23, 0, 0)->compare(LocalTime::create(23, 0, 0)));
        self::assertSame(1, LocalTime::create(23, 0, 1)->compare(LocalTime::create(23, 0, 0)));
    }

    public function testIsBefore() : void
    {
        self::assertTrue(LocalTime::create(23, 0, 0)->isBefore(LocalTime::create(23, 0, 1)));
        self::assertFalse(LocalTime::create(23, 0, 0)->isBefore(LocalTime::create(23, 0, 0)));
    }

    public function testIsAfter() : void
    {
        self::assertTrue(LocalTime::create(23, 0, 1)->isAfter(LocalTime::create(23, 0, 0)));
        self::assertFalse(LocalTime::create(23, 0, 0)->isAfter(LocalTime::create(23, 0, 0)));
    }

    public function testIsEqual() : void
    {
        self::assertFalse(LocalTime::create(23, 0, 0)->isEqual(LocalTime::create(23, 0, 1)));
        self::assertTrue(LocalTime::create(23, 0, 0)->isEqual(LocalTime::create(23, 0, 0)));
        self::assertFalse(LocalTime::create(23, 0, 1)->isEqual(LocalTime::create(23, 0, 0)));
    }

    public function testDiff() : void
    {
        $diff = LocalTime::create(23, 0, 0)->diff(LocalTime::create(23, 0, 1));
        $this->assertSame(1, $diff->s);
    }

    public function modifyProvider() : array
    {
        return [
            ['+1 day', '23:00:00'],
            ['+23 hours', '22:00:00'],
            ['+24 hours', '23:00:00'],
            ['-1 second', '22:59:59'],
            ['-1 day', '23:00:00'],
        ];
    }

    /**
     * @dataProvider modifyProvider
     */
    public function testModify(string $modification, string $expected) : void
    {
        self::assertSame($expected, (string) LocalTime::create(23, 0, 0)->modify($modification));
    }

    public function addProvider() : array
    {
        return [
            [new DateInterval('PT1S'), '23:00:01'],
            [new DateInterval('P1D'), '23:00:00'],
            [new DateInterval('PT2H'), '01:00:00'],
        ];
    }

    /**
     * @dataProvider addProvider
     */
    public function testAdd(DateInterval $interval, string $expected) : void
    {
        self::assertSame($expected, (string) LocalTime::create(23, 0, 0)->add($interval));
    }

    public function subProvider() : array
    {
        return [
            [new DateInterval('PT1S'), '22:59:59'],
            [new DateInterval('P1D'), '23:00:00'],
            [new DateInterval('PT25H'), '22:00:00'],
        ];
    }

    /**
     * @dataProvider subProvider
     */
    public function testSub(DateInterval $interval, string $expected) : void
    {
        self::assertSame($expected, (string) LocalTime::create(23, 0, 0)->sub($interval));
    }

    public function testSerialize() : void
    {
        $time = LocalTime::create(23, 0, 0);
        $serialized = serialize($time);
        self::assertSame('C:31:"DASPRiD\LocalDateTime\LocalTime":15:{s:8:"23:00:00";}', $serialized);

        $newDate = unserialize($serialized);
        self::assertSame('23:00:00', (string) $newDate);
    }
}
