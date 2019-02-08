<?php
declare(strict_types = 1);

namespace LocalDateTimeTest;

use DASPRiD\LocalDateTime\IntlLocalDateFormatter;
use DASPRiD\LocalDateTime\LocalDate;
use DASPRiD\LocalDateTime\LocalDateTime;
use DASPRiD\LocalDateTime\LocalTime;
use IntlDateFormatter;
use PHPUnit\Framework\TestCase;

final class IntlLocalDateFormatterTest extends TestCase
{
    public function testTimeFormat() : void
    {
        $formatter = IntlLocalDateFormatter::timeFormatter('en-US', IntlDateFormatter::MEDIUM);
        self::assertSame('11:00:00 PM', $formatter->format(LocalTime::create(23, 0, 0)));
    }

    public function testDateFormat() : void
    {
        $formatter = IntlLocalDateFormatter::dateFormatter('en-US', IntlDateFormatter::FULL);
        self::assertSame('Monday, January 1, 2018', $formatter->format(LocalDate::create(2018, 1, 1)));
    }

    public function testDateTimeFormat() : void
    {
        $formatter = new IntlLocalDateFormatter('en-US', IntlDateFormatter::FULL, IntlDateFormatter::MEDIUM);
        self::assertSame(
            'Monday, January 1, 2018 at 11:00:00 PM',
            $formatter->format(LocalDateTime::create(2018, 1, 1, 23, 0, 0))
        );
    }
}
