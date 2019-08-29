<?php

/**
 * Wochentagsberechnung nach https://de.wikipedia.org/wiki/Wochentagsberechnung
 */

setlocale(LC_TIME, 'de_AT.utf-8');

$day = $argv[1];
$month = $argv[2];
$year = $argv[3]; /* muss vierstellig sein */

if($argc<4 || $argc>5) {
    echo "Wrong number of arguments.";
    exit(1);
}

$m = 0;
$m = (($month - 2 - 1 ) + 12 ) % 12 + 1 ; // this is because of the modulo
$c = substr($year, 0, 2);
if($m>=11) {
    $c = substr($year-1, 0, 2);
}
$y = substr($year, 2, 2);
if($m>=11) {
    $y = substr($year-1, 2, 2);
}

$w = ($day + intval (2.6 * $m - 0.2) + $y  + intval ($y/4) + intval ($c/4) - 2*$c ) % 7;

$weekdays=[
    "Sonntag",
    "Montag",
    "Dienstag",
    "Mitwoch",
    "Donnerstag",
    "Freitag",
    "Samstag",
];
$weekday=$weekdays[$w];
echo "Eingabe: {$day}.{$month}.{$year}\n";
echo strftime("Berechnung PHP: Wochentag='%A'\n",strtotime("$year-$month-$day"));
echo "Berechnung Algorithmus: Wochentag='{$weekday}'\n";
if($argc>4 && ( $argv[4]=='-d' || $argv[4]=='--debug')) {
    echo "DEBUG: m={$m} y={$y} c={$c}\n";
}

