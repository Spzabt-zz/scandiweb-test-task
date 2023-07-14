<?php

require_once(__DIR__ . '/parts/productClassInit.php');

$sku = $name = $price = '';
$skuErr = $nameErr = $priceErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sku'])) {
        if (empty($_POST['sku'])) {
            $skuErr = 'Please, provide SKU';
        } else {
            $sku = filter_input(INPUT_POST, 'sku', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        if (empty($_POST['name'])) {
            $nameErr = 'Please, provide product name';
        } else {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        if (empty($_POST['price'])) {
            $priceErr = 'Please, provide product price';
        } else {
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        $typeId = $_POST['typeSwitcher'];
        $productTypeId = intval($typeId);

        $products[$productTypeId]->validateProductAttributes();

        if (empty($skuErr) && empty($nameErr) && empty($priceErr) && empty(
            $products[$productTypeId]->getProductAttributeFieldsErrors()
            )) {
            if (isset($products[$productTypeId])) {
                $product = $products[$productTypeId];
                $product->setSku($sku);
                $product->setName($name);
                $product->setPrice($price);
                if (!$product->saveProduct($productTypeId)) {
                    $skuErr = 'Please, provide unique SKU. Product with provided SKU already exist!';
                } else {
                    $response = array(
                        'success' => true
                    );
                    echo json_encode($response);
                    exit;
                }
            }
        }

        $response = array(
            'success' => false,
            'errors' =>
                [
                    'sku_error' => $skuErr,
                    'name_error' => $nameErr,
                    'price_error' => $priceErr
                ]
        );
        echo json_encode($response);
    }
}
