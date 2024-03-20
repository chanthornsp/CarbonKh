<?php

namespace Chanthorn\CarbonKh;

class Constant
{

    /**
     * @param int $month
     * @return string|array
     */
    public static function months(int $month = null): string|array
    {
        $khmerMonth = ['មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា', 'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ'];
        return $month === null ? $khmerMonth : $khmerMonth[$month - 1];
    }

    /**
     * @param int $month
     * @return string|array
     */
    public static function lunarMonths(int $month = null): string|array
    {
        $lunarMonths = [
            "មិគសិរ",
            "បុស្ស",
            "មាឃ",
            "ផល្គុន",
            "ចេត្រ",
            "ពិសាខ",
            "ជេស្ឋ",
            "អាសាឍ",
            "បឋមាសាឍ",
            "ទុតិយាសាឍ",
            "ស្រាពណ៍",
            "ភទ្របទ",
            "អស្សុជ",
            "កត្តិក"
        ];
        if ($month) {
            if ($month > 13 && $month < 0) return 'Undefined';
            return $lunarMonths[$month];
        } else {
            return $lunarMonths;
        }
    }

    /**
     * @param int $day
     * @return string
     */
    public static function weekDays(int $day): string
    {
        if ($day > 6 && $day < 0) return 'Undefined';
        return  ['អាទិត្យ', 'ចន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍'][$day];
    }

    /**
     * @param int $day
     * @return string
     */
    public static function weekDaysShort(int $day): string
    {
        if ($day > 6 && $day < 0) return 'Undefined';
        return ['អា', 'ច', 'អ', 'ព', 'ព្រ', 'សុ', 'ស'][$day];
    }

    /**
     * @param int $day
     * @return string
     */
    public static function moonPhase(int $day): string
    {
        if ($day > 30 && $day < 0) return 'Undefined';
        $moons = [
            '᧡', '᧢', '᧣', '᧤', '᧥', '᧦', '᧧', '᧨', '᧩', '᧪', '᧫', '᧬', '᧭', '᧮', '᧯', '᧱', '᧲', '᧳', '᧴', '᧵', '᧶', '᧷', '᧸', '᧹', '᧺', '᧻', '᧼', '᧽', '᧾', '᧿',
        ];
        return $moons[$day];
    }

    /**
     * @param int $year
     * @return string
     */
    public static function animalYear(int $year): string
    {
        if ($year > 11 && $year < 0) return 'Undefined';
        $animals = [
            'ជូត', 'ឆ្លូវ', 'ខាល', 'ថោះ', 'រោង', 'ម្សាញ់', 'មមីរ', 'មមែ', 'វក', 'រកា', 'ច', 'កុរ',
        ];
        return $animals[$year];
    }

    /**
     * @param int $year
     * @return string
     */
    public static function earaYears(int $year): string
    {
        if ($year > 9 && $year < 0) return 'Undefined';
        $earaYears = [
            'សំរឹទ្ធិស័ក', 'ឯកស័ក', 'ទោស័ក', 'ត្រីស័ក', 'ចត្វាស័ក', 'បញ្ចស័ក', 'ឆស័ក', 'សប្តស័ក', 'អដ្ឋស័ក', 'នព្វស័ក',
        ];
        return $earaYears[$year];
    }

    /**
     * @param mixed $number
     * @return mixed
     */
    public static function preparse(mixed $number): mixed
    {
        $number = str_replace(['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], $number);
        return $number;
    }

    /**
     * @param mixed $number
     * @return mixed
     */
    public static function postformat(mixed $number): mixed
    {
        $number = str_replace(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'], $number);
        return $number;
    }
}
