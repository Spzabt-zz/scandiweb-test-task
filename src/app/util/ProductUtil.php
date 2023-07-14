<?php

class ProductUtil
{
    static function compareProducts($product1, $product2): int
    {
        if ($product1->product_id < $product2->product_id) {
            return -1;
        } elseif ($product1->product_id > $product2->product_id) {
            return 1;
        } else {
            return 0;
        }
    }
}