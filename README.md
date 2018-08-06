## Simple, lightweight tool to manage time ranges

#### What is this tool supposed to help me with?

Using this tool you can 
 - add multiple time ranges
 - substract multiple time ranges
 
#### How to use this tool?

Simple... Just

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