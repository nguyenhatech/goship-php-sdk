<?php

namespace GoshipSdkPhp\Validate;
use GoshipSdkPhp\Validate\ValidateParcel;
use GoshipSdkPhp\Validate\ValidateAddress;
/**
*
*/
class ValidateShipment implements Validator
{
    protected $defaultShipments = [
        'rate' => null,
        'address_from' => [
          'name' => null,
          'phone' => null,
          'street' => null,
          'district' => null,
          'city' => null
        ],
        'address_to' => [
          'name' => null,
          'phone' => null,
          'street' => null,
          'district' => null,
          'city' => null
        ],
        'parcel' => [
          'cod' => null,
          'weight' => null,
          'mass_unit' => null,
          'width' => null,
          'height' => null,
          'length' => null,
          'distance_unit' => null
        ]
    ];

    public function validate($shipments)
    {
        $checkShipments = compareArray($this->defaultShipments, $shipments);

        if (!$checkShipments['status']) {
            foreach ($checkShipments['value'] as $error) {
                throw new \Exception("Bạn chưa nhập {$error}");
            }
        } else {
            if (!$shipments['rate']) {
                throw new \Exception("{$shipments['rate']} không được để trống");
            }
            try {
                ValidateAddress::validate($shipments['address_from']);
                ValidateAddress::validate($shipments['address_to']);
                ValidateParcel::validate($shipments['parcel']);
            } catch (\Exception $e) {
                print_r($e);die;
                throw new \Exception($e);
            }
        }
        return true;
    }
}

