<?php

namespace GoshipSdkPhp\Validate;

/**
*
*/
class ValidateParcel implements Validator
{
    const defaultParcel = [
        'cod' => NULL,
        'length' => NULL,
        'width' => NULL,
        'height' => NULL,
        'weight' => NULL,
        'mass_unit' => NULL,
        'distance_unit' => NULL
    ];

    public function validate($parcels)
    {
        $checkParcel = compareArray(self::defaultParcel, $parcels);
        if (!$checkParcel['status']) {
            foreach ($checkParcel['value'] as $error) {
                throw new \Exception("Bạn chưa nhập parcel  => {$error}");
            }
        } else {
            foreach ($parcels as $key => $parcel) {
                switch ($key) {
                    case 'mass_unit':
                        if (!$parcel) {
                                throw new \Exception("parcel  => {$key} không được để trống");
                        }
                        if (!in_array($parcel, ['g', 'kg'])) {
                            throw new \Exception("Giá trị của parcel => {$key} không hợp lệ");
                        }
                        break;
                    case 'distance_unit':
                        if (!$parcel) {
                                throw new \Exception("parcel  => {$key} không được để trống");
                        }
                        if (!in_array($parcel, ['mm', 'cm'])) {
                            throw new \Exception("Giá trị của parcel => {$key} không hợp lệ");
                        }
                        break;
                    default:
                        if (in_array($key, array_keys(self::defaultParcel))) {
                            if (!$parcel) {
                                throw new \Exception("parcel  => {$key} không được để trống");
                            }
                            if (!is_numeric($parcel)) {
                                throw new \Exception("parcel  => {$key} không phải là kiểu số");
                            }
                        }
                        break;
                }
            }
        }
        return true;
    }
}
