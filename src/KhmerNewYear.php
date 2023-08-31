<?php

namespace Chanthorn\CarbonKh;

use Carbon\Carbon;
use Chanthorn\CarbonKh\LearnSak;

class KhmerNewYear
{

    public Carbon $date;
    public int $days;
    public array $dates;

    /**
     * @param Carbon $startDate
     * @param int $numberOfNewYearDays
     * @return array
     */
    private static function datesOfKhmerNewYear(Carbon $startDate, int $numberOfNewYearDays): array
    {
        $dates = [];
        for ($i = 0; $i < $numberOfNewYearDays; $i++) {
            $dates[] = $startDate->clone()->add('day', $i);
        }
        return array_map(function ($date, $index) use ($numberOfNewYearDays) {
            return [
                'date' => $date,
                'dayName' => $index === 0 ? 'Moha Sangkranta' : ($index === $numberOfNewYearDays - 1 ? 'Veareak Laeung Sak' : 'Veareak Vanabat'),
            ];
        }, $dates, array_keys($dates));
    }

    /**
     * @param int $gregorianYear
     * @return array
     */
    public static function getKhmerNewYear(int $gregorianYear): array
    {
        $jsYear = $gregorianYear + 544 - 1182;
        $learnSak = new  LearnSak($jsYear);
        $info = $learnSak::get();
        $numberOfNewYearDay = $info['newYearsDaySotins'][0]['angsar'] == 0 ? 4 : 3;
        $epochLerngSak = Carbon::create($gregorianYear, 4, 17, 0, 0, 0);
        $lunarDateLerngSak = Lunar::findLunarDate($epochLerngSak->clone());
        $diffFromEpoch = ($lunarDateLerngSak['month']['index'] - 4) * 30 + ($lunarDateLerngSak['day'] - 1) - (($info['lunarDateLerngSak']['month'] - 4) * 30 + $info['lunarDateLerngSak']['day']);
        $result = $epochLerngSak->clone()->sub('day', $diffFromEpoch + $numberOfNewYearDay - 1);
        $time = preg_replace('/\b(\d)\b/', '0$1', "{$info['timeOfNewYear']['hour']}:{$info['timeOfNewYear']['minute']}");
        $format = "{$result->format('Y-m-d')} {$time}";
        $result = Carbon::createFromFormat('Y-m-d H:i', $format);

        return [
            'date' => $result,
            'days' => $numberOfNewYearDay,
            'dates' => self::datesOfKhmerNewYear($result, $numberOfNewYearDay),
        ];
    }

    /**
     * @param Carbon $date
     * @return int
     */
    public static function getAnimalYear(Carbon $date): int
    {
        $year = $date->year;
        $khmerNewYear = self::getKhmerNewYear($year)['date'];
        if ($date->diffInMicroseconds($khmerNewYear, false) < 0) {
            return (int) ($year + 544 + 4) % 12;
        } else {
            return (int) ($year + 543 + 4) % 12;
        }
    }

    /**
     * @param Carbon $date
     * @return int
     */
    public static function getJolakSakarajYear(Carbon $date): int
    {
        $year = $date->year;
        $khmerNewYear = self::getKhmerNewYear($year)['date'];
        if ($date->diffInMicroseconds($khmerNewYear, false) < 0) {
            return (int) $year + 544 - 1182;
        } else {
            return (int) $year + 543 - 1182;
        }
    }
}
