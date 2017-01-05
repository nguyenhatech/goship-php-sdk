<?php
require_once 'vendor/autoload.php';
require_once(__DIR__ . '/src/Constants.php');
use GoshipSdkPhp\Goship;


$login = '{"username":"lekhang2512@gmail.com","password":"111111","client_id":5,"client_secret":"SWNXqgIVkAQclPVpRMNXoWLWgqOZTNHnNdjkcbqO"}';
$shipment = '{"shipment":{"rate":"N18xXzQ0NA==","address_from":{"name":"Alvin Tran","phone":"0913292679","street":"15/03 Ngọc Hồi","district":100900,"city":100000},"address_to":{"name":"Lai Dao","phone":"0934577886","street":"102 Thái Thịnh","district":100200,"city":100000},"parcel":{"cod":50000,"weight":"220","mass_unit":"g","width":"15","height":"15","length":"15","distance_unit":"cm","metadata":"Hàng rất xịn, cấm sờ."}}}';
$rates = '{"shipment":{"address_from":{"city":"100000","district":"100300"},"address_to":{"city":"880000","district":"800300"},"parcel":{"cod":"200000","length":"10","width":"10","height":"10","weight":"400","amount":"0","mass_unit":"g","distance_unit":"cm"},"payer":0,"return_type":0,"delivery_at_date":"2016-12-09","delivery_at_time":"6:00"}}';

$goship = new Goship(json_decode($login, true));

echo "<pre>";
// print_r($goship->getAccessToken());
print_r($goship->getRates(json_decode($rates, true)));
// print_r($goship->createShipment(json_decode($shipment, true)));
echo "</pre>";