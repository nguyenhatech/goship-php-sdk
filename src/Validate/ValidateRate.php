<?php

namespace GoshipSdkPhp\Validate;
use GoshipSdkPhp\Validate\ValidateParcel;
/**
*
*/
class ValidateRate implements Validator
{
    protected $defaultRates = [
        'address_from' => [
            "city" => NULL,
            "district" => NULL
        ],
        'address_to' => [
            "city" => NULL,
            "district" => NULL
        ],
        'parcel' => [
            "cod" => NULL,
            "length" => NULL,
            "width" => NULL,
            "height" => NULL,
            "weight" => NULL,
            "amount" => NULL,
            "mass_unit" => NULL,
            "distance_unit" => NULL
        ],
    ];

    public function validate($rates)
    {
        $checkRates = compareArray($this->defaultRates, $rates);
        if (!$checkRates['status']) {
            foreach ($checkRates['value'] as $error) {
                throw new \Exception("Bạn chưa nhập {$error}");
            }
        } else {
            $checkAddressFrom = compareArray($this->defaultRates['address_from'], $rates['address_from']);
            $checkAddressTo = compareArray($this->defaultRates['address_to'], $rates['address_to']);

            if (!$checkAddressFrom['status']) {
                foreach ($checkAddressFrom['value'] as $error) {
                    throw new \Exception("Bạn chưa nhập address_from  => {$error}");
                }
            } else {
                foreach ($rates['address_from'] as $key => $address_from) {
                    if (in_array($key, array_keys($this->defaultRates['address_from']))) {
                        if (!$address_from) {
                            throw new \Exception("address_from  => {$key} không được để trống");
                        }
                        if (!is_numeric($address_from)) {
                            throw new \Exception("address_from  => {$key} không phải là kiểu số");
                        }
                    }
                }
            }

            if (!$checkAddressTo['status']) {
                foreach ($checkAddressTo['value'] as $error) {
                    throw new \Exception("Bạn chưa nhập address_to  => {$error}");
                }
            } else {
                foreach ($rates['address_to'] as $key => $address_to) {
                    if (in_array($key, array_keys($this->defaultRates['address_to']))) {
                        if (!$address_to) {
                            throw new \Exception("address_to  => {$key} không được để trống");
                        }
                        if (!is_numeric($address_to)) {
                            throw new \Exception("address_to  => {$key} không phải là kiểu số");
                        }
                    }
                }
            }

            try {
                ValidateParcel::validate($rates['parcel']);
            } catch (\Exception $e) {
                print_r($e);die;
                throw new \Exception($e);
            }
        }
        return true;
    }
}

