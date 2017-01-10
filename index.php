<?php
require_once 'vendor/autoload.php';
use GoshipSdkPhp\Goship;


$login = [
    'username' => "lekhang2512@gmail.com",
    'password' => "111111",
    'client_id' => 5,
    'client_secret' => "SWNXqgIVkAQclPVpRMNXoWLWgqOZTNHnNdjkcbqO",
    'workspace' => "dev",
    'version' => "v1"
];

$shipment = [
    'address_from' => [
        'name' => "Alvin Tran",
        'phone' => "0913292679",
        'street' => "15/03 Ngọc Hồi",
        'city' => 100000,
        'district' => 100900
    ],
    'address_to' => [
        'name' => "Lai Dao",
        'phone' => "0934577886",
        'street' => "102 Thái Thịnh",
        'city' => 100000,
        'district' => 100200
    ],
    'parcel' => [
        'cod' => 50000,
        'length' => 15,
        'width' => 15,
        'height' => 15,
        'weight' => 220,
        'mass_unit' => "g",
        'distance_unit' => "cm",
        'metadata' => "Hàng rất xịn, cấm sờ."
    ]
];

$goship = new Goship($login);

echo "<pre>";
print_r($goship->getToken());
$rates = $goship->getRates($shipment);
print_r($rates);
print_r($goship->createShipment($rates[0]));
// print_r($goship->detailShipment("GOO7DL72G6X"));
// print_r($goship->trackShipment("GOO7DL72G6X"));
echo "</pre>";