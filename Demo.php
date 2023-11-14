<?php

require_once __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;
// use Chanthorn\CarbonKh\ToKhmerDate;

// $date = Carbon::parse('2000-01-01');
// $khmerDate = new ToKhmerDate($date);
// echo $khmerDate->format();

echo khmerDate()->format();

print_r(khmerDate('2024-01-01')::khNewYear());
// or
print_r(KhmerNewYearDate(2024));
