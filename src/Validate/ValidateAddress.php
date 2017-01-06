<?php

namespace GoshipSdkPhp\Validate;

/**
*
*/
class ValidateAddress implements Validator
{
    const defaultAddress = [
        'name' => NULL,
        'phone' => NULL,
        'street' => NULL,
        'district' => NULL,
        'city' => NULL
    ];

    public function validate($addresses)
    {
        $checkAddress = compareArray(self::defaultAddress, $addresses);
        if (!$checkAddress['status']) {
            foreach ($checkAddress['value'] as $error) {
                throw new \Exception("Bạn chưa nhập address => {$error}");
            }
        } else {
            foreach ($addresses as $key => $address) {
                switch ($key) {
                    case 'city':
                    case 'district':
                        if (!$address) {
                                throw new \Exception("address  => {$key} không được để trống");
                        }
                        if (!is_numeric($address)) {
                                throw new \Exception("address  => {$key} không phải là kiểu số");
                        }
                        break;
                    default:
                        if (in_array($key, array_keys(self::defaultAddress))) {
                            if (!$address) {
                                throw new \Exception("address  => {$key} không được để trống");
                            }
                        }
                        break;
                }
            }
        }
        return true;
    }
}

 ?>