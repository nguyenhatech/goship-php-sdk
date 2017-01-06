<?php

function compareArray(array $current, array $prev)
{
    $check = array_diff(array_keys($current), array_keys($prev));
    if (count($check)) {
        $errors = [
            'status' => false,
            'value' => $check,
        ];
        return $errors;
    }
    return ['status' => true];
}

 ?>