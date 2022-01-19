<?php

namespace App\Libraries;

class CommonHelper
{
    /**
     * Used for set the validation related error messages.
     * 
     * @param Array $errors 
     * @return string 
     */
    public static function customErrorResponse(Array $errors): string
    {
        $error_str = '';
        foreach ($errors as $error) {
            $error_str .= str_replace('.', ',', $error[0]);
        }
        return rtrim($error_str, ',');
    }
}
