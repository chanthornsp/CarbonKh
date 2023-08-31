<?php

namespace Chanthorn\CarbonKh;

use Carbon\Carbon;
use  Chanthorn\CarbonKh\Lunar;
use  Chanthorn\CarbonKh\Constant;
use  Chanthorn\CarbonKh\KhmerNewYear;

class ToKhmerDate
{

    private static Carbon $date;

    /**
     * Constructor
     * @param Carbon $date?
     * @param string $format?
     */
    public function __construct(Carbon $date = null)
    {
        self::$date = $date ?? Carbon::now();
        self::$date = self::$date->clone();
    }

    /**
     * @param string $format?
     * @return string
     */
    public static function format(string $format = ''): string
    {
        $lunar = Lunar::findLunarDate(self::$date);
        $month = $lunar['month'];
        $beYear = $lunar['years']['BE'];
        // get day of the week index
        $dayOfWeek = self::$date->dayOfWeek;
        $earaYears = Constant::earaYears((int)(KhmerNewYear::getJolakSakarajYear(self::$date) % 10));
        $animalYear = Constant::animalYear((int)KhmerNewYear::getAnimalYear(self::$date));

        if (!$format) {
            $result =  "ថ្ងៃ" . Constant::weekDays($dayOfWeek) . " " . $lunar['period']['day'] . $lunar['period']['moon'] . " ខែ" . $month['name'] . " ឆ្នាំ" . $animalYear . " " . $earaYears . " ពុទ្ធសករាជ " . $beYear;
            return Constant::postformat($result);
        } else {
            $formatRules = [
                'W' => function () use ($dayOfWeek) {
                    return Constant::weekDays($dayOfWeek);
                },
                'w' => function () use ($dayOfWeek) {
                    return Constant::weekDaysShort($dayOfWeek);
                },
                'd' => function () use ($lunar) {
                    return $lunar['period']['day'];
                },
                'D' => function () use ($lunar) {
                    $moonDay = $lunar['period']['day'];
                    return strlen($moonDay) === 1 ? "០{$moonDay}" : $moonDay;
                },
                'N' => function () use ($lunar) {
                    return $lunar['period']['moon'];
                },
                'n' => function () use ($lunar) {
                    return $lunar['period']['moon'] === 'កើត' ? 'ក' : 'រ';
                },
                'o' => function () use ($lunar) {
                    return Constant::moonPhase($lunar['day']);
                },
                'm' => function () use ($month) {
                    return $month['name'];
                },
                'M' => function () use ($month) {
                    return Constant::months($month['index']);
                },
                'a' => function () use ($animalYear) {
                    return $animalYear;
                },
                'e' => function () use ($earaYears) {
                    return $earaYears;
                },
                'b' => function () use ($beYear) {
                    return $beYear;
                },
                'c' => function () use ($beYear) {
                    return $beYear;
                },
                'j' => function () use ($lunar) {
                    return $lunar['years']['JE'];
                },
            ];
            $result = preg_replace_callback('/[a-zA-Z]/', function ($matches) use ($formatRules) {
                return $formatRules[$matches[0]]();
            }, $format);
            return Constant::postformat($result);
        }
    }

    /**
     * @return array
     */
    public static function  khNewYear(): array
    {
        return KhmerNewYear::getKhmerNewYear(self::$date->year);
    }
}
