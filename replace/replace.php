<?php
/**
 * This script replaces text data 'visibility' attribute to integer value.
 * Can be used for replacing any data for export products csv file
 */

$nonVisible = '1';
$visibleCatalog = '2';
$visibleSearch = '3';
$visibleBoth = '4';

$nonVisibleLabel = 'Not Visible Individually';
$visibleCatalogLabel = 'Catalog';
$visibleSearchLabel = 'Search';
$visibleBothLabel = 'Catalog, Search';

//$fileFrom = 'catalog_product_no_images.csv';
$fileFrom = 'export_all_products_new.csv';
$fileTo = 'export_all_products_NEW.csv';

if ($fileRead = fopen($fileFrom, "r")) {
    if ($fileWrite = fopen($fileTo, 'w')) {
        while (!feof($fileRead)) {
            /*$line = fgets($fileRead);
            $checkline = str_replace(PHP_EOL, '', $line);
            $lineCheck = explode(',', $checkline);*/

            $line = fgetcsv($fileRead);

            switch ($line[10]) {
                case $nonVisibleLabel:
                    $line[10] = $nonVisible;
                    break;
                case $visibleCatalogLabel:
                    $line[10] = $visibleCatalog;
                    break;
                case $visibleSearchLabel:
                    $line[10] = $visibleSearch;
                    break;
                case $visibleBothLabel:
                    $line[10] = $visibleBoth;
                    break;
                default:
                    break;
            }

            $res = fputcsv($fileWrite, $line, ',', '"');
            /*if (!empty($lineCheck[0])) {
                $res = fwrite($fileWrite, $line);
            }*/
        }
    }
    fclose($fileWrite);
    fclose($fileRead);
}