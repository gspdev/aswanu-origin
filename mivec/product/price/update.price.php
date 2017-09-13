<?php
require 'config.php';
/*
$sql = "SELECT * FROM " . __TABLE_PRODUCT__ . " ORDER BY entity_id DESC";
$data = getFinalPrice(535);
print_r($data);
*/

$_file = "price.csv";

//set log for update price
$_logFile = "log.price.csv";
$handle = fopen(__DATA_PATH__ . $_logFile , "wb+");
$_header = array("id" , "origin price" , "price" , "percent");
fputcsv($handle , $_header);

if ($_content = getCsvContent(__DATA_PATH__ . $_file)) {
    sort($_content);
    //print_r($_content);exit;
    $i = 1;
    foreach ($_content as $_con) {
        $_id = $_con[0];
        $_price = $_con[3]; //price in file
        if (!empty($_price) != 0) {
            //echo $_price . "</p>";
            if (hasProduct($_id)) {
                $_data = getFinalPrice($_id, $_price);
                //currency exchange
                $_data['price'] = $_data['price'] / 6.53;
                try {
                    if (updatePrice($_id, $_data['price'])) {
                        echo $_id . " was success updated</p>";

                        updateLogToFile($handle, $_data);
                        usleep(10);
                        //if ($i == 1) break;
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
        //if ($i==10) break;
        $i++;
    }
}

fclose($handle);