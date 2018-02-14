<?php
include 'codes.php';

$fileFrom = 'files/zones.csv';
$fileTo = 'files/liveRates.csv';

$header = explode(',', 'Country,Region/State,City,Zip From,Zip To,Shipping Group,Weight >,Weight <=,Price >,Price <=,Item >,Item <=,Customer Group,Shipping Price,Percentage,Delivery Type,Algorithm');

$codes = getCodes();
$notAvailable = '#N/A';

$carrier = 'FedEx'; /** Change it */
//$carrier = 'Dicom'; /** Change it */
//$carrier = '24/7'; /** Change it */
$wineGroup = 'WINE';
$country = 'US';
$rates = getRates();

if ($fileRead = fopen($fileFrom, "r")) {
    if ($fileWrite = fopen($fileTo, 'a')) {

        if ($carrier == 'FedEx') {
            $a = fputcsv($fileWrite, $header, ',');
        }

        $shippingTable = [];
        $i = 0;
        $prevPos = 0;

        while (!feof($fileRead)/* && $i < 100*/) {
            $line = fgetcsv($fileRead);

            if ($line[2] != $notAvailable) {
                if (strpos($line[5], $carrier) !== false) {  /** If Ship All is available */
                    populateOrigin($line, $codes, '*', $shippingTable, $i, $prevPos);
                } elseif (strpos($line[6], $carrier) !== false) {
                    populateOrigin($line, $codes, $wineGroup, $shippingTable, $i, $prevPos);
                }
            }
        }

        fclose($fileRead);

        foreach ($shippingTable as $item) {
            foreach ($rates as $rate) {
                fputcsv($fileWrite, populate($item, $rate), ',');
            }
        }
    }

    fclose($fileWrite);
}