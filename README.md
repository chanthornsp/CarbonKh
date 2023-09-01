# Khmer Lunar Date with Carbon

# Installation

```bash
composer require chanthorn/carbon-kh
```

# Usage

```php
require_once __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;
use Chanthorn\CarbonKh\ToKhmerDate;

    $date = Carbon::parse('2023-01-01');
    $khmerDate = new ToKhmerDate($date);

    echo $khmerDate::format(); // ថ្ងៃអាទិត្យ ១០កើត ខែបុស្ស ឆ្នាំខាល ចត្វាស័ក ពុទ្ធសករាជ ២៥៦៦
    echo $khmerDate::format('dN ថ្ងៃW ខែm ព.ស. b'); // ១០កើត ថ្ងៃអាទិត្យ ខែបុស្ស ព.ស. ២៥៦៦

    print_r($khmerDate::khNewYear());
    // array:3 [
    //   "date" => Carbon\Carbon
    //   "days" => 3
    //   "dates" => array:3
    // ]

```

# Use Helper Function

```php
require_once __DIR__ . '/vendor/autoload.php';

echo khmerDate('2024-01-01')->format();
//ថ្ងៃច័ន្ទ ៥រោច ខែមិគសិរ ឆ្នាំថោះ បញ្ចស័ក ពុទ្ធសករាជ ២៥៦៧

print_r(khmerDate('2024-01-01')::khNewYear());

print_r(KhmerNewYearDate(2024));
// array:3 [
//   "date" => Carbon\Carbon
//   "days" => 4
//   "dates" => array:4
// ]
```

# Usage with Laravel

```php
use Chanthorn\CarbonKh\ToKhmerDate;

    $date = Carbon::parse('2023-01-01');
    $khmerDate = new ToKhmerDate($date);

    echo $khmerDate::format(); // ថ្ងៃអាទិត្យ ១០កើត ខែបុស្ស ឆ្នាំខាល ចត្វាស័ករាជ ២៥៦៦
    echo $khmerDate::format('dN ថ្ងៃW ខែm ព.ស. b'); // ១០កើត ថ្ងៃអាទិត្យ ខែបុស្ស ព.ស. ២៥៦៦

    print_r($khmerDate::khNewYear());
    // array:3 [
    //   "date" => Carbon\Carbon
    //   "days" => 3
    //   "dates" => array:3
    // ]

```

## Format

Check out format [here](https://github.com/ThyrithSor/momentkh#format)

# Authors

- This library is Ported from [khmercal](https://github.com/seanghay/khmercal) by [Seanghay](https://github.com/seanghay) and [momentkh](https://github.com/ThyrithSor/momentkh) by [Thyrith Sor](https://github.com/ThyrithSor) in to PHP.
