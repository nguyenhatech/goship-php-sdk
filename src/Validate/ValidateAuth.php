<?php

namespace GoshipSdkPhp\Validate;

/**
*
*/
class ValidateAuth implements Validator
{
    protected $defaultAuth = [
        'username' => NULL,
        'password' => NULL,
        'client_id' => NULL,
        'client_secret' => NULL,
    ];

    public function validate($auth)
    {
        $checkAuth = compareArray($this->defaultAuth, $auth);
        if (!$checkAuth['status']) {
            foreach ($checkAuth['value'] as $error) {
                throw new \Exception("Bạn chưa nhập {$error}");
            }
        } else {
            foreach ($auth as $key => $value) {
                switch ($key) {
                    case 'client_id':
                        if (!is_numeric($value)) {
                            throw new \Exception("{$key} không phải là kiểu số");
                        }
                        break;
                    default:
                        if (!$value) {
                            throw new \Exception("{$key} không được để trống");
                        }
                        break;
                }
            }
        }
        return true;
    }
}

 ?>