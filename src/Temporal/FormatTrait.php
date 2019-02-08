<?php
declare(strict_types = 1);

namespace DASPRiD\LocalDateTime\Temporal;

use DateTimeImmutable;
use InvalidArgumentException;

trait FormatTrait
{
    /**
     * Creates a new temporal from a format and input string.
     *
     * @see http://php.net/manual/en/datetime.createfromformat.php for possible format values.
     * @throws InvalidArgumentException when format string contains invalid characters.
     * @throws InvalidArgumentException when input string doesn't match format string.
     */
    public static function createFromFormat(string $format, string $input) : self
    {
        if (! preg_match(
            '(^
                (?:
                    \\\\.
                    |
                    [' . self::getAllowedFormatCharacters() . ']
                )*
            $)x',
            $format
        )) {
            throw new InvalidArgumentException(sprintf(
                'Format string "%s" contains invalid characters.',
                $format
            ));
        }

        $dateTime = DateTimeImmutable::createFromFormat($format, $input, UtcTimeZone::instance());

        if (false === $dateTime) {
            throw new InvalidArgumentException(sprintf(
                'Input string "%s" does not match format string "%s".',
                $input,
                $format
            ));
        }

        return new self($dateTime);
    }

    public function format(string $format) : string
    {
        $format = preg_replace_callback(
            '([' . self::getEscapedOutputCharacters() . '])',
            function (array $match) : string {
                return '\\' . $match[0];
            },
            $format
        );

        return $this->getInternalDateTime()->format($format);
    }

    abstract public function getInternalDateTime() : DateTimeImmutable;
    abstract protected static function getEscapedOutputCharacters() : string;
    abstract protected static function getAllowedFormatCharacters() : string;
    abstract protected function __construct(DateTimeImmutable $dateTime);
}
