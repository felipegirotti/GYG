<?php

namespace GYG\Domain\Validator;

class InputParamsValidator
{

    /**
     * @param array $params
     * @return bool
     */
    public static function validate(array $params)
    {
        if (!array_key_exists(1, $params)
            || !array_key_exists(2, $params)
            || !array_key_exists(3, $params)
            || !array_key_exists(4, $params)
        ) {
            throw new \InvalidArgumentException('Must be 4 parameters');
        }

        if (!filter_var($params[1], FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
            throw new \UnexpectedValueException('The first param must be a url valid');
        }
        
        if (intval($params[4]) === 0) {
            throw new \UnexpectedValueException('The fourth param must be a integer gran than 0');
        }

        return true;
    }
}
