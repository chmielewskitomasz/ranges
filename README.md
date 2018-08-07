## Simple, lightweight tool to manage time ranges
[![Build Status](https://travis-ci.org/chmielewskitomasz/ranges.svg?branch=master)](https://travis-ci.org/chmielewskitomasz/ranges)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chmielewskitomasz/ranges/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chmielewskitomasz/ranges/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/chmielewskitomasz/ranges/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/chmielewskitomasz/ranges/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/chmielewskitomasz/ranges/badges/build.png?b=master)](https://scrutinizer-ci.com/g/chmielewskitomasz/ranges/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/chmielewskitomasz/ranges/v/stable)](https://packagist.org/packages/chmielewskitomasz/ranges)
[![License](https://poser.pugx.org/chmielewskitomasz/ranges/license)](https://packagist.org/packages/chmielewskitomasz/ranges)

[![Donate on PayPal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://paypal.me/chmielewskitomasz)

#### What is this tool supposed to help me with?

Using this tool you can 
 - add multiple time ranges
 - substract multiple time ranges
 
#### How to use this tool?

Simple... 

To add ranges just...

```php
require_once __DIR__ . '/vendor/autoload.php';

use Hop\Ranges\Range;
use Hop\Ranges\StdRangesCalculator;

$range1 = new Range(
    new \DateTime('2018-01-01 00:00:00'),
    new \DateTime('2018-01-02 00:05:00')
);

$range2 = new Range(
    new \DateTime('2018-01-02 00:06:00'),
    new \DateTime('2018-01-02 00:07:00')
);

$range3 = new Range(
    new \DateTime('2018-01-02 00:06:30'),
    new \DateTime('2018-01-02 00:10:00')
);

$calculator = new StdRangesCalculator();

$ranges = $calculator->sum(
    $range1,
    $range2,
    $range3
);

print_r($ranges);

// prints out

Array
(
    [0] => Hop\Ranges\Range Object
        (
            [dateFrom:Hop\Ranges\Range:private] => DateTime Object
                (
                    [date] => 2018-01-01 00:00:00.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

            [dateTo:Hop\Ranges\Range:private] => DateTime Object
                (
                    [date] => 2018-01-02 00:05:00.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

        )

 ---->   // this is merged!
    [1] => Hop\Ranges\Range Object
        (
            [dateFrom:Hop\Ranges\Range:private] => DateTime Object
                (
                    [date] => 2018-01-02 00:06:00.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

            [dateTo:Hop\Ranges\Range:private] => DateTime Object
                (
                    [date] => 2018-01-02 00:10:00.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

        )

)

```

To substract ranges do

```php

require_once __DIR__ . '/vendor/autoload.php';

use Hop\Ranges\Range;
use Hop\Ranges\StdRangesCalculator;

$range1 = new Range(
    new \DateTime('2018-01-01 00:00:00'),
    new \DateTime('2018-01-05 00:05:00')
);

$range2 = new Range(
    new \DateTime('2018-01-02 00:06:00'),
    new \DateTime('2018-01-02 00:07:00')
);


$calculator = new StdRangesCalculator();

$ranges = $calculator->sub(
    $range1,
    $range2
);

print_r($ranges);

// prints out

Array
(
    [0] => Hop\Ranges\Range Object
        (
            [dateFrom:Hop\Ranges\Range:private] => DateTime Object
                (
                    [date] => 2018-01-01 00:00:00.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

            [dateTo:Hop\Ranges\Range:private] => DateTime Object
                (
                    [date] => 2018-01-02 00:05:59.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

        )

    [1] => Hop\Ranges\Range Object
        (
            [dateFrom:Hop\Ranges\Range:private] => DateTime Object
                (
                    [date] => 2018-01-02 00:07:01.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

            [dateTo:Hop\Ranges\Range:private] => DateTime Object
                (
                    [date] => 2018-01-05 00:05:00.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

        )

)

```


Enjoy!

&copy; Tomasz Chmielewski