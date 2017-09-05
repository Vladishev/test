<?php
/**
 * Script deletes part of url string
 */

$fileFrom = 'plaza_categories_redirects_updated.csv';
$fileTo = 'plaza_categories_redirects_updated_DELETED_WWW.csv';
$replace = [
    'http://www.plazaart.com/'
];

if ($fileRead = fopen($fileFrom, "r")) {
    if ($fileWrite = fopen($fileTo, 'w')) {
        while (!feof($fileRead)) {
            $line = fgets($fileRead);
            $newLine = str_replace($replace, '', $line);
            $res = fwrite($fileWrite, $newLine);
        }
    }
    fclose($fileWrite);
    fclose($fileRead);
}

