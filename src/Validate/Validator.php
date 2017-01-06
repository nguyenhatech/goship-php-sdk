<?php

namespace GoshipSdkPhp\Validate;

interface Validator
{

    /**
     * Validates the given $data against whatever rule(s) the implementation of this
     * interface enforces.  Returns an array of error messages, or an empty array
     * if $data is valid.
     *
     * @param mixed $data
     * @return array An array of error messages
     */
    public function validate($data);

}

 ?>