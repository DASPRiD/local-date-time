<?php
declare(strict_types = 1);

namespace LocalDateTimeTest;

use DASPRiD\LocalDateTime\LocalDate;
use DASPRiD\LocalDateTime\LocalDateTime;
use DASPRiD\LocalDateTime\LocalTime;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LocalDateTimeTimeTest extends TestCase
{
    public function testCreate() : void
    {
        self::assertSame('2018-01-01T23:00:00', (string) LocalDateTime::create(2018, 1, 1, 23, 0, 0));
    }

    public function testCreateFromNow() : void
    {
        $before = date('Y-m-d\\TH:i:s');
        $date = LocalDateTime::createFromNow();
        $after = date('Y-m-d\\TH:i:s');

        self::assertGreaterThanOrEqual($before, (string) $date);
        self::assertLessThanOrEqual($after, (string) $date);
    }

    public function testCreateFromNowWithTimeZone() : void
    {
        $earlierDate = LocalDateTime::createFromNow(new DateTimeZone('Etc/GMT+12'));
        $laterDate = LocalDateTime::createFromNow(new DateTimeZone('Etc/GMT+11'));

        $this->assertSame((string) $earlierDate->modify('+1 hour'), (string) $laterDate);
    }

    public function testCreateFromDateTime() : void
    {
        $date = LocalDateTime::createFromDateTime(new DateTimeImmutable('2018-01-01T01:00:00+05:00'));
        self::assertSame('2018-01-01T01:00:00', (string) $date);
    }

    public function testCreateFromFormat() : void
    {
        $date = LocalDateTime::createFromFormat('l, F j, Y H:i:s', 'Monday, January 1, 2018 23:00:00');
        self::assertSame('2018-01-01T23:00:00', (string) $date);
    }

    public function testCreateFromLocalDateAndTime() : void
    {
        $date = LocalDateTime::createFromLocalDateAndTime(
            LocalDate::create(2018, 1, 1),
            LocalTime::create(23, 0, 0)
        );
        self::assertSame('2018-01-01T23:00:00', (string) $date);
    }

    public function testCreateFromInvalidFormat() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Format string "e" contains invalid characters.');
        LocalDateTime::createFromFormat('e', '2018-01-01 23:00:00');
    }

    public function testCreateFromNonMatchingFormat() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Input string "2018-01-01" does not match format string "l, F j, Y"');
        LocalDateTime::createFromFormat('l, F j, Y', '2018-01-01');
    }

    public function testFormat() : void
    {
        self::assertSame(
            'Monday, January 1, 2018 23:00:00 e',
            LocalDateTime::create(2018, 1, 1, 23, 0, 0)->format('l, F j, Y H:i:s e')
        );
    }

    public function testDateGetters() : void
    {
        $date = LocalDateTime::create(2018, 1, 1, 23, 0, 0);
        self::assertSame(2018, $date->getYear());
        self::assertSame(1, $date->getMonth());
        self::assertSame(1, $date->getDay());
    }

    public function testCompare() : void
    {
        self::assertSame(
            -1,
            LocalDateTime::create(2018, 1, 1, 23, 0, 0)->compare(LocalDateTime::create(2018, 1, 2, 0, 0, 0))
        );
        self::assertSame(
            0,
            LocalDateTime::create(2018, 1, 1, 23, 0, 0)->compare(LocalDateTime::create(2018, 1, 1, 23, 0, 0))
        );
        self::assertSame(
            1,
            LocalDateTime::create(2018, 1, 2, 0, 0, 0)->compare(LocalDateTime::create(2018, 1, 1, 23, 0, 0))
        );
    }

    public function testIsBefore() : void
    {
        self::assertTrue(
            LocalDateTime::create(2018, 1, 1, 23, 0, 0)->isBefore(LocalDateTime::create(2018, 1, 2, 0, 0, 0))
        );
        self::assertFalse(
            LocalDateTime::create(2018, 1, 1, 23, 0, 0)->isBefore(LocalDateTime::create(2018, 1, 1, 23, 0, 0))
        );
    }

    public function testIsAfter() : void
    {
        self::assertTrue(
            LocalDateTime::create(2018, 1, 2, 0, 0, 0)->isAfter(LocalDateTime::create(2018, 1, 1, 23, 0, 0))
        );
        self::assertFalse(
            LocalDateTime::create(2018, 1, 1, 23, 0, 0)->isAfter(LocalDateTime::create(2018, 1, 1, 23, 0, 0))
        );
    }

    public function testIsEqual() : void
    {
        self::assertFalse(
            LocalDateTime::create(2018, 1, 1, 23, 0, 0)->isEqual(LocalDateTime::create(2018, 1, 2, 0, 0, 0))
        );
        self::assertTrue(
            LocalDateTime::create(2018, 1, 1, 23, 0, 0)->isEqual(LocalDateTime::create(2018, 1, 1, 23, 0, 0))
        );
        self::assertFalse(
            LocalDateTime::create(2018, 1, 2, 0, 0, 0)->isEqual(LocalDateTime::create(2018, 1, 1, 23, 0, 0))
        );
    }

    public function testDiff() : void
    {
        $diff = LocalDateTime::create(2018, 1, 1, 23, 0, 0)->diff(LocalDateTime::create(2018, 1, 2, 0, 0, 0));
        $this->assertSame(1, $diff->h);
    }

    public function modifyProvider() : array
    {
        return [
            ['+1 day', '2018-01-02T23:00:00'],
            ['+23 hours', '2018-01-02T22:00:00'],
            ['+24 hours', '2018-01-02T23:00:00'],
            ['-1 second', '2018-01-01T22:59:59'],
            ['-1 day', '2017-12-31T23:00:00'],
        ];
    }

    /**
     * @dataProvider modifyProvider
     */
    public function testModify(string $modification, string $expected) : void
    {
        self::assertSame($expected, (string) LocalDateTime::create(2018, 1, 1, 23, 0, 0)->modify($modification));
    }

    public function addProvider() : array
    {
        return [
            [new DateInterval('P1D'), '2018-01-02T23:00:00'],
            [new DateInterval('PT23H'), '2018-01-02T22:00:00'],
            [new DateInterval('PT24H'), '2018-01-02T23:00:00'],
        ];
    }

    /**
     * @dataProvider addProvider
     */
    public function testAdd(DateInterval $interval, string $expected) : void
    {
        self::assertSame($expected, (string) LocalDateTime::create(2018, 1, 1, 23, 0, 0)->add($interval));
    }

    public function subProvider() : array
    {
        return [
            [new DateInterval('P1D'), '2017-12-31T23:00:00'],
            [new DateInterval('PT24H'), '2017-12-31T23:00:00'],
            [new DateInterval('PT25H'), '2017-12-31T22:00:00'],
        ];
    }

    /**
     * @dataProvider subProvider
     */
    public function testSub(DateInterval $interval, string $expected) : void
    {
        self::assertSame($expected, (string) LocalDateTime::create(2018, 1, 1, 23, 0, 0)->sub($interval));
    }

    public function testToLocalDate() : void
    {
        self::assertSame('2018-01-01', (string) LocalDateTime::create(2018, 1, 1, 23, 0, 0)->toLocalDate());
    }

    public function testToLocalTime() : void
    {
        self::assertSame('23:00:00', (string) LocalDateTime::create(2018, 1, 1, 23, 0, 0)->toLocalTime());
    }

    public function testSerialize() : void
    {
        $date = LocalDateTime::create(2018, 1, 1, 23, 0, 0);
        $serialized = serialize($date);
        self::assertSame('C:35:"DASPRiD\LocalDateTime\LocalDateTime":27:{s:19:"2018-01-01T23:00:00";}', $serialized);

        $newDate = unserialize($serialized);
        self::assertSame('2018-01-01T23:00:00', (string) $newDate);
    }
}
