<?php

namespace App\Exceptions;

use Exception;

class MaxArrayException extends Exception
{
    public static function countArray()
    {
        return response()->json("The quantity of elements must be 9 (mandatory)", 500);
    }
}
