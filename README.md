# LocalDateTime

[![Build Status](https://travis-ci.org/DASPRiD/local-date-time.svg?branch=master)](https://travis-ci.org/DASPRiD/local-date-time)
[![Coverage Status](https://coveralls.io/repos/github/DASPRiD/local-date-time/badge.svg?branch=master)](https://coveralls.io/github/DASPRiD/local-date-time?branch=master)
[![Reference Status](https://www.versioneye.com/php/dasprid:local-date-time/reference_badge.svg?style=flat)](https://www.versioneye.com/php/dasprid:local-date-time/references)
[![Latest Stable Version](https://poser.pugx.org/dasprid/local-date-time/v/stable)](https://packagist.org/packages/dasprid/local-date-time)
[![Total Downloads](https://poser.pugx.org/dasprid/local-date-time/downloads)](https://packagist.org/packages/dasprid/local-date-time)
[![License](https://poser.pugx.org/dasprid/local-date-time/license)](https://packagist.org/packages/dasprid/local-date-time)

LocalDateTime is a lightweight wrapper around PHP's own DateTime object, allowing for smooth work with local date, time
and date time without worrying about time zones. Each temporal object allows the same ways of comparision, modification
and formatting as the underlying DateTime object.

Additionally this library comes with a wrapper around IntlDateFormatter, specifically aimed to format local temporals.
For ease of integration, Doctrine types for these temporals are provided as well. 

## Installation

Install via composer:

```bash
$ composer require dasprid/local-date-time
```

## Getting started

There are three temporal objects supplied:

- `DASPRiD\LocalDateTime\LocalDate`
- `DASPRiD\LocalDateTime\LocalTime`
- `DASPRiD\LocalDateTime\LocalDateTime`

All of these are constructed in a similar fashion through named constructors. For simplicity, the following examples
illustrate working with the `LocalDate` object.

### Creation

A `LocalDate` object can be constructed in multiple ways:

```php
<?php
use DASPRiD\LocalDateTime\LocalDate;

$date = LocalDate::createFromNow();
$date = LocalDate::create(2019, 12, 31);
$date = LocalDate::createFromFormat('Y-m-d', '2019-12-31');
$date = LocalDate::createFromDateTime(new DateTime('2019-12-31'));
```

When creating a `LocalDate` from a `DateTime` object, it will be interpreted in the time zone the `DateTime` object is
set to. If you wish to interpret it in a different time zone, you must change it on the `DateTime` object beforehand.

### Modification

As said before, the `LocalDate` supports the same modification methods as a regular `DateTime` object:

- `::modify(string $modify) : self`
- `::add(DateInterval $interval) : self`
- `::sub(DateInterval $interval) : self`

When a modification contains units not meant for temporal (e. g. hours for a `LocalDate`), it will be applied to the
underlying `DateTime` object and afterwards removed. For instance:

```php
<?php
use DASPRiD\LocalDateTime\LocalDate;

$date = LocalDate::create(2019, 12, 31);
$newDate = $date->modify('-1 hour');

// $newDate will now be 2019-12-30, with the internal DateTime time reset to 00:00:00
```

### Comparision

Comparision works exactly the same as with `DateTime` objects, except that since PHP code cannot override comparators,
the following methods were made available:

- `::compare(self $other) : int`
- `::isBefore(self $other) : bool`
- `::isAfter(self $other) : bool`
- `::isEqual(self $other) : bool`
- `::diff(self $other, bool $absolute = false) : DateInterval`

### Formatting

Formatting also works the same as you are used to. Depending on the temporal object, specific formatting characters are
not supported and will be returned as is. For instance, no local temporal supports time zone information, and a 
`LocalDate` cannot return any time information.

```php
<?php
use DASPRiD\LocalDateTime\LocalDate;

$date = LocalDate::create(2019, 12, 31);
echo $date->format('d.m.Y');

// Outputs: 31.12.2019
```

To format a temporal in a specific locale other than English, you will need to use the `IntlLocalDateFormatter`, which
requires `ext-intl` to be installed. To format the above date in a German locale, you'd do the following:

```php
<?php
use DASPRiD\LocalDateTime\LocalDate;
use DASPRiD\LocalDateTime\IntlLocalDateFormatter;

$date = LocalDate::create(2019, 12, 31);
$formatter = IntlLocalDateFormatter::dateFormatter('de-DE', IntlDateFormatter::FULL);
echo $formatter->format($date);
```

## Doctrine integration

To use the local temporals with Doctrine, the following Doctrine types are supplied:

- `DASPRiD\LocalDateTime\Doctrine\LocalDateType` (name: `localdate`)
- `DASPRiD\LocalDateTime\Doctrine\LocalTimeType` (name: `localtime`)
- `DASPRiD\LocalDateTime\Doctrine\LocalDateTimeType` (name: `localdatetime`)
