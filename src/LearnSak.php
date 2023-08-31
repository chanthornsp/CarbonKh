<?php

namespace Chanthorn\CarbonKh;

use Carbon\Carbon;

class LearnSak
{

    private static $jsYear;
    private static $has366Days;
    private static $isAdhikameas;
    private static $isChantreathimeas;
    private static $dayLerngSak;


    /**
     * @param int $year
     */

    public function __construct(int $year)
    {
        self::$jsYear = $year;
        self::$has366Days = self::getHas366Days(self::$jsYear);
        self::$isAdhikameas = self::getIsAdhikameas(self::$jsYear);
        self::$isChantreathimeas = self::getIsChantreathimeas(self::$jsYear);
        self::$dayLerngSak = (self::getInfo(self::$jsYear)['harkun'] - 2) % 7;
    }

    /**
     * @param int $year
     */
    private static function getInfo($year): array
    {
        $h = 292207 * $year + 373;
        $harkun = floor($h / 800) + 1;
        $kromathopol = 800 - ($h % 800);

        $a = 11 * $harkun + 650;
        $avaman = $a % 692;
        $bodithey = ($harkun + floor($a / 692)) % 30;
        return [
            'harkun' => $harkun,
            'kromathopol' => $kromathopol,
            'avaman' => $avaman,
            'bodithey' => $bodithey,
        ];
    }

    /**
     * @param int $year
     * @return bool
     */
    private static function getHas366Days($year): bool
    {
        $kromathopol = self::getInfo($year)['kromathopol'];
        return $kromathopol <= 207;
    }

    /**
     * @param int $year
     * @return bool
     */
    private static function getIsAdhikameas($year): bool
    {
        $infoOfYear = self::getInfo($year)['bodithey'];
        $infoOfNextYear = self::getInfo($year + 1)['bodithey'];
        return !($infoOfYear === 25 && $infoOfNextYear === 5) && ($infoOfYear > 24 || $infoOfNextYear < 6 || ($infoOfYear === 24 && $infoOfNextYear === 6));
    }

    /**
     * @param int $year
     * @return bool
     */
    private static function getIsChantreathimeas($year): bool
    {
        $infoOfYear = self::getInfo($year)['avaman'];
        $infoOfNextYear = self::getInfo($year + 1)['avaman'];
        $infoOfPrevYear = self::getInfo($year - 1)['avaman'];
        $has366Days = self::getHas366Days($year);
        return ($has366Days && $infoOfYear < 127) || (!($infoOfYear === 137 && $infoOfNextYear === 0) && ((!$has366Days && $infoOfYear < 138) || ($infoOfPrevYear === 137 && $infoOfYear === 0)));
    }

    /**
     * @return bool
     */
    private static function jesthHas30(): bool
    {
        $isAthikameas = self::$isAdhikameas;
        $tmp = self::$isChantreathimeas;
        if ($isAthikameas && self::$isChantreathimeas) {
            $tmp = false;
        }
        if (!$tmp && self::getIsAdhikameas(self::$jsYear - 1) && self::getIsChantreathimeas(self::$jsYear - 1)) {
            $tmp = true;
        }
        return $tmp;
    }

    /**
     * @return array
     */
    private static function lunarDateLerngSak(): array
    {
        $bodithey = self::getInfo(self::$jsYear)['bodithey'];
        if (self::getIsAdhikameas(self::$jsYear - 1) && self::getIsChantreathimeas(self::$jsYear - 1)) {
            $bodithey = ($bodithey + 1) % 30;
        }
        return [
            'day' => $bodithey >= 6 ? $bodithey - 1 : $bodithey,
            'month' => $bodithey >= 6 ? 4 : 5,
        ];
    }

    /**
     * @param int $sotin
     * @return array
     */
    private static function getSunInfo($sotin): array
    {
        $infoOfPreviousYear = self::getInfo(self::$jsYear - 1);

        /**
         * @return int
         */
        $sunAverageAsLibda = function () use ($sotin, $infoOfPreviousYear): int {
            $r2 = 800 * $sotin + $infoOfPreviousYear['kromathopol'];
            $reasey = floor($r2 / 24350); // រាសី
            $r3 = $r2 % 24350;
            $angsar = floor($r3 / 811); // អង្សា
            $r4 = $r3 % 811;
            $l1 = floor($r4 / 14);
            $libda = $l1 - 3; // លិប្ដា
            return 30 * 60 * $reasey + 60 * $angsar + $libda;
        };

        /**
         * @return int
         */
        $leftOver = function () use ($sunAverageAsLibda): int {
            $s1 = 30 * 60 * 2 + 60 * 20;
            $leftOver = $sunAverageAsLibda() - $s1; // មធ្យមព្រះអាទិត្យ - R2.A20.L0
            if ($sunAverageAsLibda() < $s1) {
                // បើតូចជាង ខ្ចី ១២ រាសី
                $leftOver += 30 * 60 * 12;
            }
            return $leftOver;
        };

        /**
         * @return int
         */
        $kaen = function () use ($leftOver): int {
            return floor($leftOver() / (30 * 60));
        };

        /**
         * @return array
         */
        $lastLeftOver = function () use ($kaen, $leftOver): array {
            $rs = -1;
            if (in_array($kaen(), [0, 1, 2])) {
                $rs = $kaen();
            } else if (in_array($kaen(), [
                3, 4, 5
            ])) {
                $rs = 30 * 60 * 6 - $leftOver(); // R6.A0.L0 - leftover
            } else if (in_array($kaen(), [
                6, 7, 8
            ])) {
                $rs = $leftOver() - 30 * 60 * 6; // leftover - R6.A0.L0
            } else if (in_array($kaen(), [
                9, 10, 11
            ])) {
                $rs = 30 * 60 * 11 + 60 * 29 + 60 - $leftOver(); // R11.A29.L60 - leftover
            }
            return [
                'reasey' => floor($rs / (30 * 60)),
                'angsar' => floor(($rs % (30 * 60)) / 60),
                'libda' => $rs % 60,
            ];
        };

        /**
         * @return int
         */
        $khan = function () use ($lastLeftOver): int {
            if ($lastLeftOver()['angsar'] >= 15) {
                return 2 * $lastLeftOver()['reasey'] + 1;
            } else {
                return 2 * $lastLeftOver()['reasey'];
            }
        };

        /**
         * @return int
         */
        $pouichalip = function () use ($lastLeftOver): int {
            if ($lastLeftOver()['angsar'] >= 15) {
                return 60 * ($lastLeftOver()['angsar'] - 15) + $lastLeftOver()['libda'];
            } else {
                return 60 * $lastLeftOver()['angsar'] + $lastLeftOver()['libda'];
            }
        };

        /**
         * @return array
         */
        $phol = function () use ($khan, $pouichalip): array {
            /**
             * @param int $khan
             * @return array
             */
            $chhayaSun = function ($khan): array {
                $multiplicities = [35, 32, 27, 22, 13, 5];
                $chhayas = [0, 35, 67, 94, 116, 129];
                switch ($khan) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        return [
                            'multiplicity' => $multiplicities[$khan],
                            'chhaya' => $chhayas[$khan],
                        ];
                    default:
                        return [
                            'multiplicity' => 0,
                            'chhaya' => 134,
                        ];
                }
            };

            $val = $chhayaSun($khan());
            $q = floor(($pouichalip() * $val['multiplicity']) / 900);
            return [
                'reasey' => 0,
                'angsar' => floor(($q + $val['chhaya']) / 60),
                'libda' => ($q + $val['chhaya']) % 60,
            ];
        };

        /**
         * @return int
         */
        $sunInaugurationAsLibda = function () use ($sunAverageAsLibda, $phol, $kaen): int {
            $pholAsLibda =
                30 * 60 * $phol()['reasey'] + 60 * $phol()['angsar'] + $phol()['libda'];
            if ($kaen() <= 5) {
                return $sunAverageAsLibda() - $pholAsLibda;
            } else {
                return $sunAverageAsLibda() + $pholAsLibda;
            }
        };

        return [
            'sunAverageAsLibda' => $sunAverageAsLibda,
            'khan' => $khan,
            'pouichalip' => $pouichalip,
            'phol' => $phol,
            'sunInaugurationAsLibda' => $sunInaugurationAsLibda,
        ];
    }

    /**
     * @return array
     */
    private static function newYearsDaySotins(): array
    {
        $sotins = self::getHas366Days(self::$jsYear - 1)
            ? [363, 364, 365, 366]
            : [362, 363, 364, 365]; // សុទិន
        return array_map(function ($sotin) {
            $sunInfo = self::getSunInfo($sotin);
            return [
                'sotin' => $sotin,
                'reasey' => floor($sunInfo['sunInaugurationAsLibda']() / (30 * 60)),
                'angsar' => floor(($sunInfo['sunInaugurationAsLibda']() % (30 * 60)) / 60), // អង្សាស្មើសូន្យ គីជាថ្ងៃចូលឆ្នាំ, មួយ ឬ ពីរ ថ្ងៃបន្ទាប់ជាថ្ងៃវ័នបត ហើយ ថ្ងៃចុងក្រោយគីឡើងស័ក
                'libda' => $sunInfo['sunInaugurationAsLibda']() % 60,
            ];
        }, $sotins);
    }

    /**
     * @return array
     */
    private static function timeOfNewYear(): array
    {
        $sotinNewYear = array_filter(self::newYearsDaySotins(), function ($sotin) {
            return $sotin['angsar'] == 0;
        });
        // reset key
        $sotinNewYear = array_values($sotinNewYear);

        if (count($sotinNewYear) > 0) {
            $libda = $sotinNewYear[0]['libda']; // ២៤ ម៉ោង មាន ៦០លិប្ដា
            $minutes = 24 * 60 - $libda * 24;
            return [
                'hour' => (int) floor($minutes / 60),
                'minute' => (int) $minutes % 60,
            ];
        } else {
            throw new \Exception(
                "Plugin is facing wrong calculation on new years hour. No sotin with angsar = 0",
            );
        }
    }

    /**
     * @return array
     */
    public static function get(): array
    {
        return [
            'jsYear' => self::$jsYear,
            'harkun' => self::getInfo(self::$jsYear)['harkun'],
            'kromathopol' => self::getInfo(self::$jsYear)['kromathopol'],
            'avaman' => self::getInfo(self::$jsYear)['avaman'],
            'bodithey' => self::getInfo(self::$jsYear)['bodithey'],
            'has366Days' => self::$has366Days,
            'isAdhikameas' => self::$isAdhikameas,
            'isChantreathimeas' => self::$isChantreathimeas,
            'jesthHas30' => self::jesthHas30(),
            'dayLerngSak' => self::$dayLerngSak,
            'lunarDateLerngSak' => self::lunarDateLerngSak(),
            'newYearsDaySotins' => self::newYearsDaySotins(),
            'timeOfNewYear' => self::timeOfNewYear(),
        ];
    }
}
