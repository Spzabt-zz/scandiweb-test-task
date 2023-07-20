<?php

namespace App\Util;

class ProductUtil
{
    public static function compareProducts($product1, $product2): int
    {
        if ($product1->product_id < $product2->product_id) {
            return -1;
        } elseif ($product1->product_id > $product2->product_id) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function countOfDigits($number): string {
        $number = str_replace(['.', ','], '', $number);

        return strlen($number);
    }

    public static function isInvalidInput($input): bool {
        return (strpos(strtolower($input), "e"));
    }
}
