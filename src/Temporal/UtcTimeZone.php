<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Temporal;

use DateTimeZone;

final class UtcTimeZone
{
    /**
     * @var DateTimeZone
     */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * Returns a singleton instance for a UTC time zone.
     */
    public static function instance() : DateTimeZone
    {
        return self::$instance ?: self::$instance = new DateTimeZone('UTC');
    }
}
