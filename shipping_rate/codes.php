<?php

function getCodes()
{
    $data = array(
        array('US', 'AL', 'Alabama'), array('US', 'AK', 'Alaska'), array('US', 'AS', 'American Samoa'),
        array('US', 'AZ', 'Arizona'), array('US', 'AR', 'Arkansas'), array('US', 'AF', 'Armed Forces Africa'),
        array('US', 'AA', 'Armed Forces Americas'), array('US', 'AC', 'Armed Forces Canada'),
        array('US', 'AE', 'Armed Forces Europe'), array('US', 'AM', 'Armed Forces Middle East'),
        array('US', 'AP', 'Armed Forces Pacific'), array('US', 'CA', 'California'), array('US', 'CO', 'Colorado'),
        array('US', 'CT', 'Connecticut'), array('US', 'DE', 'Delaware'), array('US', 'DC', 'District of Columbia'),
        array('US', 'FM', 'Federated States Of Micronesia'), array('US', 'FL', 'Florida'), array('US', 'GA', 'Georgia'),
        array('US', 'GU', 'Guam'), array('US', 'HI', 'Hawaii'), array('US', 'ID', 'Idaho'), array('US', 'IL', 'Illinois'),
        array('US', 'IN', 'Indiana'), array('US', 'IA', 'Iowa'), array('US', 'KS', 'Kansas'), array('US', 'KY', 'Kentucky'),
        array('US', 'LA', 'Louisiana'), array('US', 'ME', 'Maine'), array('US', 'MH', 'Marshall Islands'),
        array('US', 'MD', 'Maryland'), array('US', 'MA', 'Massachusetts'), array('US', 'MI', 'Michigan'),
        array('US', 'MN', 'Minnesota'), array('US', 'MS', 'Mississippi'), array('US', 'MO', 'Missouri'),
        array('US', 'MT', 'Montana'), array('US', 'NE', 'Nebraska'), array('US', 'NV', 'Nevada'),
        array('US', 'NH', 'New Hampshire'), array('US', 'NJ', 'New Jersey'), array('US', 'NM', 'New Mexico'),
        array('US', 'NY', 'New York'), array('US', 'NC', 'North Carolina'), array('US', 'ND', 'North Dakota'),
        array('US', 'MP', 'Northern Mariana Islands'), array('US', 'OH', 'Ohio'), array('US', 'OK', 'Oklahoma'),
        array('US', 'OR', 'Oregon'), array('US', 'PW', 'Palau'), array('US', 'PA', 'Pennsylvania'),
        array('US', 'PR', 'Puerto Rico'), array('US', 'RI', 'Rhode Island'), array('US', 'SC', 'South Carolina'),
        array('US', 'SD', 'South Dakota'), array('US', 'TN', 'Tennessee'), array('US', 'TX', 'Texas'),
        array('US', 'UT', 'Utah'), array('US', 'VT', 'Vermont'), array('US', 'VI', 'Virgin Islands'),
        array('US', 'VA', 'Virginia'), array('US', 'WA', 'Washington'), array('US', 'WV', 'West Virginia'),
        array('US', 'WI', 'Wisconsin'), array('US', 'WY', 'Wyoming')
    );
    $result = [];

    foreach ($data as $state) {
        $result[$state[2]] = $state[1];
    }

    return $result;
}

function getRates()
{
    $file = 'files/Rates_FedEx.csv'; /** Change it */
    $start = true;
    $rates = [];

    if ($fileRead = fopen($file, "r")) {
        while (!feof($fileRead)) {

            if ($start) {
                $start = false;
                continue;
            }

            $rates[] = fgetcsv($fileRead);
        }
    }

    fclose($fileRead);
    array_shift($rates);

    return $rates;
}

function populate($line, $rate)
{
    if ($rate[0] == 1) {
        $line[6] = '*';
    } else {
        $line[6] = $rate[0] - 1;
    }

    $line[7] = $rate[0];
    $line[13] = substr($rate[$line[17] - 1], 1);

    array_pop($line);

    return $line;
}

function populateOrigin($line, $codes, $wineGroup, &$shippingTable, &$i, &$prevPos)
{
    $carrier = 'FedEx'; /** Change it */
    $shippingItem = [];
    $changed = true;

    if (count($shippingTable)) {
        $prevLine = $shippingTable[$prevPos];
    } else {
        $prevLine = false;
    }

    /** Change it */
    if ($prevLine !== false && (((int)$line[0] - 1) == (int)$prevLine[4]) && ($codes[$line[1]] == $prevLine[1]) && ($line[2] == $prevLine[17]) && ($prevLine[5] == $wineGroup)) {
        $changed = false;
    }

    if ($changed) {
        for ($k = 0; $k < 18; $k++) {
            switch ($k) {
                case 0:
                    $shippingItem[$k] = 'US';
                    break;
                case 1:
                    $shippingItem[$k] = $codes[$line[1]];
                    break;
                case 3:
                    $shippingItem[$k] = $line[0];
                    break;
                case 4:
                    $shippingItem[$k] = $line[0];
                    break;
                case 5:
                    $shippingItem[$k] = $wineGroup;
                    break;
                case 14:
                    $shippingItem[$k] = 1;
                    break;
                case 15:
                    $shippingItem[$k] = $carrier;
                    break;
                case 16:
                    $shippingItem[$k] = 'ORDER';
                    break;
                case 17:
                    $shippingItem[$k] = $line[2]; /** Change it */
                    break;
                default:
                    $shippingItem[$k] = '*';
                    break;
            }
        }

        $shippingTable[$i] = $shippingItem;
        $prevPos = $i;
        $i++;
    } else {
        $shippingTable[$prevPos][4] = $line[0];
    }

}
