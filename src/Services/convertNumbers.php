<?php

namespace App\Services;

class convertNumbers
{
    public function convert(int $num)
    {
        if ($num > 999 && $num < 1000000) return round($num / 1000, 1) . "K";
        else if ($num > 999999 && $num < 1000000000)  return round($num / 1000000, 1) . "M";
        else if ($num > 999999999 && $num < 1000000000000) return round($num / 1000000000, 1) . "B";
        else if (gettype($num) !== "integer") throw new \Exception("Provided data is not a number", 500);
        else return $num;
    }
}
