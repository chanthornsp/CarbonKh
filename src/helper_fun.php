<?php

use Carbon\Carbon;
use Chanthorn\CarbonKh\ToKhmerDate;
use Chanthorn\CarbonKh\KhmerNewYear;

if (!function_exists('khmerDate')) {
    function khmerDate(Carbon|string $date = null): ToKhmerDate
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        return new ToKhmerDate($date);
    }
}

if (!function_exists('KhmerNewYearDate')) {
    function KhmerNewYearDate(int $gregorianYear): array
    {
        return KhmerNewYear::getKhmerNewYear($gregorianYear);
    }
}
