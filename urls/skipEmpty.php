<?php
/**
 * Replace script for custom csv without text quotes
 */


$fileFrom = 'plaza_categories_redirects_updated_DELETED_WWW.csv';
$fileTo = 'plaza_categories_redirects_updated_DELETED_EMPTY.csv';

if ($fileRead = fopen($fileFrom, "r")) {
    if ($fileWrite = fopen($fileTo, 'w')) {
        while (!feof($fileRead)) {
            $line = fgets($fileRead);
            $checkline = str_replace(PHP_EOL, '', $line);
            $lineCheck = explode(',', $checkline);
            if (!empty($lineCheck[1])) {
                $res = fwrite($fileWrite, $line);
            }
        }
    }
    fclose($fileWrite);
    fclose($fileRead);
}

