<?php

require_once(__DIR__ . '/vendor/autoload.php');

use App\Controller\ProductController;

$productController = new ProductController();

$routes = [
    '/product-web-app/' => 'src/resources/templates/productList.php',
    '/product-web-app/remove-product' => 'removeProduct',
    '/product-web-app/add-product' => 'src/resources/templates/productAdd.php',
    '/product-web-app/add-product/handle-validation' => 'saveProduct',
    '/product-web-app/add-product/switch-type' => 'switchType'
];

$url = $_SERVER['REQUEST_URI'];

$parsedUrl = parse_url($url);
$cleanUrl = $parsedUrl['path'] ?? '';

$route = $routes[$cleanUrl] ?? 'src/resources/templates/error.php';

if (method_exists($productController, $route)) {
    switch ($route) {
        case 'saveProduct':
            $productController->saveProduct();
            break;
        case 'switchType':
            $productController->switchType();
            break;
        case 'removeProduct':
            $productController->removeProduct();
            header('Location: /product-web-app/');
            break;
        default:
            exit(0);
    }
    exit(0);
}

if ($url === '/product-web-app/') {
    $productsFromDb = $productController->sortProducts();
}

if ($url === '/product-web-app/add-product/switch-type') {
    $paramValue = $_GET['q'] ?? '';
    $route .= '?q=' . urlencode($paramValue);
}

include __DIR__ . '/' . $route;
