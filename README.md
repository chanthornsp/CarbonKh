# Khmer Lunar Date with Carbon

# Usage

```php
require_once __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;
$date = Carbon::parse('2023-01-01');
$khmerDate = new ToKhmerDate($date);
echo $khmerDate->format();
```

# Author

- This library is Ported from [khmercal](https://github.com/seanghay/khmercal) by [Seanghay](https://github.com/seanghay) and [momentkh](https://github.com/ThyrithSor/momentkh) by [Thyrith Sor](https://github.com/ThyrithSor) in to PHP.
