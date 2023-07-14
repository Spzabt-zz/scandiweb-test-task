<?php

require_once(__DIR__ . '/parts/header.php');

$q = intval($_GET['q']);

if (isset($products[$q])) {
    $product = $products[$q];
    $product->displayProductAttributeInputFields();
}
