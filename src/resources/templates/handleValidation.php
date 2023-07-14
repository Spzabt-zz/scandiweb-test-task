<?php

require_once(__DIR__ . '/parts/productClassInit.php');

$sku = $name = $price = '';
$skuErr = $nameErr = $priceErr = $typeSwitcherErr = '';

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

        $productTypeId = $_POST['typeSwitcher'];
        if (!is_numeric($productTypeId)) {
            $typeSwitcherErr = 'Please, select product type';
            $response = array(
                'success' => false,
                'errors' =>
                    [
                        'sku_error' => $skuErr,
                        'name_error' => $nameErr,
                        'price_error' => $priceErr,
                        'productTypeError' => $typeSwitcherErr
                    ]
            );
            echo json_encode($response);
            exit;
        } else {
            $productTypeId = intval($productTypeId);
        }

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
                ],
            'errorAttrs' => []
        );

        foreach ($products[$productTypeId]->getProductAttributeFieldsErrorArray() as $attrFieldError) {
            $clazz = null;

            try {
                $clazz = new ReflectionClass($products[$productTypeId]);
            } catch (ReflectionException $e) {
                echo 'Reflection error: ' . $e->getMessage();
            }

            foreach ($clazz->getProperties(ReflectionProperty::IS_PRIVATE) as $property) {
                if ($property->getValue($products[$productTypeId]) === $attrFieldError) {
                    $propertyName = $property->getName();
                    $response['errorAttrs'][$propertyName] = $attrFieldError;
                }
            }
        }

        echo json_encode($response);
    }
}
