<?php
/**
 * @package rroycedev/laravel-artisan-db-cli
 * @author  Ron Royce <rroycedev@gmail.com>
 * @version 1.0
 *
 * @section LICENSE
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details at
 * https://www.gnu.org/copyleft/gpl.html
 *
 */

/**
 * Arguments
 * 1: String to search</Arguments:>
 * 2: Regular expression</Arguments:>
 */

if ($argc != 3 && $argc != 4) {
    die("Syntax: php -f testregex.php <filename> <pattern_name> <is_split>\n");
}

$filename = $argv[1];
$patternName = $argv[2];

$isSplit = false;

if ($argc == 4) {
    if (strtoupper($argv[3]) == "Y" || strtoupper($argv[3]) == "1") {
        $isSplit = true;
    }
}
$patterns = require __DIR__ . '/../Schema/Patterns.php';

$string = file_get_contents($filename);

$pattern = $patterns['test'][$patternName];

echo "Pattern: [$pattern]\n";
echo "String : [$string]\n";

if ($isSplit) {
    $columns = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY);

    echo "Columns: \n";

    print_r($columns);
} else {
    $matched = preg_match($pattern, $string, $matches);

    if ($matched) {
        echo "Matches:\n\n";
        print_r($matches);
        echo "\n";
    } else {
        echo "No matches found\n";
    }
}
