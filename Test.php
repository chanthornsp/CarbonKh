<?php

require_once __DIR__ . '/vendor/autoload.php';

use Chanthorn\CarbonKh\Lunar;

$rusule = Lunar::findLunarDate(Carbon\Carbon::now());
echo '<pre>';
print_r($rusule);
echo '</pre>';
