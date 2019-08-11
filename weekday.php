<?php

/**
 * Wochentagsberechnung nach https://de.wikipedia.org/wiki/Wochentagsberechnung
 */

/**
 * @return array
 */
function handleCommandLine($argv): array {

    $argc = count($argv);
    if ($argc < 4 || $argc > 5) {
        echo "Wrong number of arguments.";
        exit(1);
    }

    $day = $argv[1];
    $month = $argv[2];
    $year = $argv[3]; /* muss vierstellig sein */

    if(isset($argv[4]) && ($argv[4] == '-d' || $argv[4] == '--debug')) {
        $debug = true;
    } else {
        $debug = false;
    }

    return [$day, $month, $year, $debug];
}

/**
 * @param int $weekDayNumber
 * @return string
 */
function weekdayNumberToWeekday(int $weekDayNumber) : string {
    $weekDayNames = [
        1 => "Montag",
        2 => "Dienstag",
        3 => "Mittwoch",
        4 => "Donnerstag",
        5 => "Freitag",
        6 => "Samstag",
        0 => "Sonntag",
    ];

    if (isset($weekDayNames[$weekDayNumber])) {
        $weekDay = $weekDayNames[$weekDayNumber];
    } else {
        echo "Error: Unknown w={$weekDayNumber}\n";
        exit(1);
    }
    return $weekDay;
}

/**
 * @param $day
 * @param $month
 * @param $year
 * @return int
 */
function dateToWeekdayNumber($day, $month, $year, bool $debug=false): int {
    $d = $day;
    $m = 0;
    $m = (($month - 2 - 1) + 12) % 12 + 1; // this is because of the modulo
    $c = substr($year, 0, 2);
    if ($m >= 11) {
        $c = substr($year - 1, 0, 2);
    }
    $y = substr($year, 2, 2);
    if ($m >= 11) {
        $y = substr($year - 1, 2, 2);
    }

    $weekDayNumber = ($d + intval(2.6 * $m - 0.2) + $y + intval($y / 4) + intval($c / 4) - 2 * $c) % 7;
    if ($debug) {
        echo "DEBUG: m={$m} y={$y} c={$c}\n";
    }
    return $weekDayNumber;
}

/**
 * @param $day
 * @param $month
 * @param $year
 * @param string $weekDay
 */
function outputResult($day, $month, $year, string $weekDay): void {
    echo "Eingabe: {$day}.{$month}.{$year}\n";
    echo strftime("Berechnung PHP: Wochentag='%A'\n", strtotime("$year-$month-$day"));
    echo "Berechnung Algorithmus: Wochentag='{$weekDay}'\n";
}

/**
 * @param $argv
 * @return int
 */
function main($argv): int {
    setlocale(LC_TIME, 'de_AT.utf-8');
    list($day, $month, $year, $debug) = handleCommandLine($argv);

    $weekDayNumber = dateToWeekdayNumber($day, $month, $year, $debug);
    $weekDay = weekdayNumberToWeekday($weekDayNumber);

    outputResult($day, $month, $year, $weekDay);
    return 0;
}

return main($argv);

