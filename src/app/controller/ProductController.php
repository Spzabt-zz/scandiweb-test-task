<?php

namespace App\Controller;

use App\Model\Book;
use App\Model\DvdDisc;
use App\Model\Furniture;
use App\Util\ProductUtil;
use ReflectionClass;
use ReflectionException;

class ProductController
{
    private const MAX_SKU_LENGTH = 255;
    private const MAX_NAME_LENGTH = 255;
    private const MAX_PRICE_LENGTH = 10;
    private array $products;
    private array $response;

    public function __construct()
    {
        $this->products = array(
            1 => new DvdDisc(),
            2 => new Book(),
            3 => new Furniture()
        );
    }

    public function saveProduct()
    {
        $sku = $name = $price = '';
        $skuErr = $nameErr = $priceErr = $typeSwitcherErr = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['sku'])) {
                if (empty($_POST['sku'])) {
                    $skuErr = 'Please, provide SKU';
                } else if (strlen($_POST['sku']) > self::MAX_SKU_LENGTH) {
                    $skuErr = 'SKU length must be less or equals ' . self::MAX_SKU_LENGTH . ' symbols!';
                } else {
                    $sku = filter_input(INPUT_POST, 'sku', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }

                if (empty($_POST['name'])) {
                    $nameErr = 'Please, provide product name';
                } else if (strlen($_POST['name']) > self::MAX_NAME_LENGTH) {
                    $nameErr = 'Product name length must be less or equals ' . self::MAX_NAME_LENGTH . ' symbols!';
                } else {
                    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }

                if (empty($_POST['price'])) {
                    $priceErr = 'Please, provide product price';
                } else if (ProductUtil::countOfDigits($_POST['price']) > self::MAX_PRICE_LENGTH) {
                    $priceErr = 'Product price must be up to ' . self::MAX_PRICE_LENGTH . ' digits!';
                } else {
                    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }

                $productTypeId = $_POST['typeSwitcher'];
                if (!is_numeric($productTypeId)) {
                    $typeSwitcherErr = 'Please, select product type';
                    $this->response = array(
                        'success' => false,
                        'errors' =>
                            [
                                'sku_error' => $skuErr,
                                'name_error' => $nameErr,
                                'price_error' => $priceErr,
                                'productTypeError' => $typeSwitcherErr
                            ]
                    );
                    echo json_encode($this->response);
                    return;
                } else {
                    $productTypeId = intval($productTypeId);
                }

                $this->products[$productTypeId]->validateProductAttributes();

                if (empty($skuErr) && empty($nameErr) && empty($priceErr) && empty(
                    $this->products[$productTypeId]->getProductAttributeFieldsErrors()
                    )) {
                    if (isset($this->products[$productTypeId])) {
                        $product = $this->products[$productTypeId];
                        $product->setSku($sku);
                        $product->setName($name);
                        $product->setPrice($price);
                        if (!$product->saveProduct($productTypeId)) {
                            $skuErr = 'Please, provide unique SKU. Product with provided SKU already exist!';
                        } else {
                            $this->response = array(
                                'success' => true
                            );
                            echo json_encode($this->response);
                            return;
                        }
                    }
                }

                $this->response = array(
                    'success' => false,
                    'errors' =>
                        [
                            'sku_error' => $skuErr,
                            'name_error' => $nameErr,
                            'price_error' => $priceErr
                        ],
                    'errorAttrs' => []
                );

                foreach ($this->products[$productTypeId]->getProductAttributeFieldsErrorArray() as $attrFieldError) {
                    $clazz = null;

                    try {
                        $clazz = new ReflectionClass($this->products[$productTypeId]);
                    } catch (ReflectionException $e) {
                        echo 'Reflection error: ' . $e->getMessage();
                    }

                    foreach ($clazz->getProperties() as $property) {
                        $property->setAccessible(true);
                        if ($property->getValue($this->products[$productTypeId]) === $attrFieldError) {
                            $propertyName = $property->getName();
                            $this->response['errorAttrs'][$propertyName] = $attrFieldError;
                        }
                    }
                }
            }
        }

        echo json_encode($this->response);
    }

    public function removeProduct() {
        if (isset($_POST['massDeleteSubmit']) && isset($_POST['productIds'])) {
            $productIds = $_POST['productIds'];

            foreach ($productIds as $productId) {
                foreach ($this->products as $product => $productValue) {
                    $productValue->deleteProduct($productId);
                }
            }
        }
    }

    public function switchType()
    {
        $q = intval($_GET['q']);

        if (isset($this->products[$q])) {
            $product = $this->products[$q];
            $product->displayProductAttributeInputFields();
        }
    }

    public function sortProducts(): array {
        $productsFromDb = [];
        foreach ($this->products as $product => $productValue) {
            $productsFromDb = array_merge($productsFromDb, $productValue->getProductList());
        }
        usort($productsFromDb, ['App\Util\ProductUtil', 'compareProducts']);

        return $productsFromDb;
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}
