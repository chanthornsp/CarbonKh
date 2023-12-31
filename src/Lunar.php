<?php

namespace Chanthorn\CarbonKh;

use Carbon\Carbon;

class Lunar
{

    private static $date;

    /**
     * @param int $year
     */
    private static function aharakoune($year): int
    {
        return ($year * 292207 + 373) % 800;
    }

    /**
     * @param int $year
     */
    private static function harakoune($year): int
    {
        return (int) floor(($year * 292207 + 373) / 800) + 1;
    }

    /**
     * @param int $year
     */
    private static function avomane($year): int
    {
        return (11 * self::harakoune($year) + 650) % 692;
    }

    /**
     * @param int $year
     */
    private static function regularLeap($year): bool
    {
        return 800 - self::aharakoune($year) <= 207;
    }

    /**
     * @param int $year
     */
    private static function bodethey($year): int
    {
        $ha = self::harakoune($year);
        return ($ha + floor(($ha * 11 + 650) / 692)) % 30;
    }

    /**
     * @param Carbon $end
     */
    private static function lunarDiffDays($end): int
    {
        $count = 0;
        $x = 1970 - 638 + 1;
        $y = $end->year - 638;
        if ($x > $y) {
            $x = $y;
            $y = $x;
        }
        while ($x < $y) {
            $count += self::daysInYear($x++);
        }
        return $count;
    }

    /**
     * @param int $year
     */
    private static function daysInYear($year): int
    {
        if (self::jaisLeap($year)) {
            return 384;
        }
        if (self::greatLeap($year)) {
            return 355;
        }
        return 354;
    }

    /**
     * @param int $year
     */
    private static function jaisLeap($year): bool
    {
        $b0 = self::bodethey($year);
        $b1 = self::bodethey($year + 1);
        return $b0 > 24 || $b0 < 6 || ($b0 === 24 && $b1 === 6) || ($b0 === 25 && $b1 === 5);
    }

    /**
     * @param int $year
     */
    private static function langSak($year): array
    {
        $i = self::sakDay($year);
        return ['month' => 3 + (int) ($i >= 6 && $i <= 29), 'day' => $i];
    }

    /**
     * @param int $year
     */
    private static function sakDay($year): int
    {
        $bo = self::bodethey($year);
        $bl0 = self::jaisLeap($year - 1);
        if (!$bl0 || ($bl0 && !self::isProtetinLeap($year - 1))) {
            if ($bo < 6) {
                return $bo + 1;
            }
            return $bo;
        }
        return $bo + 1;
    }

    /**
     * @param int $year
     */
    private static function greatLeap($year): bool
    {
        $value = self::isProtetinLeap($year);
        if (self::jaisLeap($year) && $value) {
            $value = false;
        }
        return $value;
    }

    /**
     * @param int $year
     */
    private static function isProtetinLeap($year): bool
    {
        $avomane0 = self::avomane($year);
        $avomane1 = self::avomane($year + 1);
        $normal = self::regularLeap($year);
        $value = $normal && $avomane0 < 127;

        if (!$normal) {
            if ($avomane0 === 137 && $avomane1 === 0) {
                $value = false;
            } elseif ($avomane0 < 138) {
                $value = true;
            }
        }

        if (!$value) {
            $value = self::isProtetinLeap($year - 1) && self::jaisLeap($year - 1);
        }
        return $value;
    }

    /**
     * @param Carbon $end
     */
    private static function diffDays($end): int
    {
        return abs(round((($end->timestamp * 1000) - 286596e5) / (1000 * 60 * 60 * 24)));
    }

    /**
     * @param int $year
     */
    private static function monthsOfYear($year): array
    {
        $ath = self::jaisLeap($year);
        $great = self::greatLeap($year);
        $items = [];
        for ($i = 0; $i < 12 + (int) $ath; $i++) {
            $j = $i;
            if ($ath && $j >= 8) {
                $j--;
            }
            $items[] = 29 + (int) ($j % 2 != 0) + (($j == 6 && $great) ? 1 : 0);
        }
        return $items;
    }


    // find lunar date
    /**
     * @param Carbon $date
     * @return array
     */
    public static function findLunarDate($date = null): array
    {
        if ($date === null) {
            $date = Carbon::now();
        }
        self::$date = $date->clone()->subHours(7);
        $CE = self::$date->year;

        $y = $CE - 638;
        $day = abs(self::diffDays(self::$date) - self::lunarDiffDays(self::$date)) + 1;
        $BE = $CE + 543 + ($day > 162 ? 1 : 0);

        $len = self::daysInYear($y);
        if ($day > $len) {
            $day = $day - $len;
            $y++;
        }


        $m = 0;
        $lengthOfYear = self::monthsOfYear($y);
        foreach ($lengthOfYear as $key => $month) {
            if ($day <= $month) {
                break;
            }
            $day -= $month;
            $m++;
        }
        $sak = self::langSak($y - 1);
        $JE = $y - 1 - ($sak['month'] > $m || ($sak['month'] === $m && $sak['day'] > self::$date->day) ? 1 : 0);

        $yearMonths = Constant::lunarMonths();
        $yearMonths = array_values(array_filter($yearMonths, function ($item, $key) use ($lengthOfYear) {
            if (count($lengthOfYear) == 12) {
                return $key != 8 && $key != 9;
            } else {
                return $key != 7;
            }
        }, ARRAY_FILTER_USE_BOTH));
        $ZODIAC_YEARS = [
            "ជូត",
            "ឆ្លូវ",
            "ខាល",
            "ថោះ",
            "រោង",
            "ម្សាញ់",
            "មមីរ",
            "មមែ",
            "វក",
            "រកា",
            "ច",
            "កុរ",
        ];

        // dump(Constant::weekDays(1));
        // dump(Constant::preparse('១២៣៤៥៦៧៨៩០'));
        // dd(Constant::postformat('1234567890'));

        return [
            'day' => $day,
            'period' => [
                'day' => (($day - 1) % 15) + 1,
                'moon' => $day > 15 ? 'រោច' : 'កើត',
            ],
            'zodiac' => $ZODIAC_YEARS[(($JE + 1) % 12 + 10) % 12],
            'years' => [
                'JE' => $JE,
                'CE' => $CE,
                'BE' => $BE,
            ],
            'length' => $len,
            'monthLength' => $lengthOfYear[$m],
            'month' => ['name' => $yearMonths[$m], 'index' => $m],
            'months' => $yearMonths,
        ];
    }
}
