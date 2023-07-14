<?php
require(__DIR__ . '/../../../app/config/DbConnection.php');
require(__DIR__ . '/../../../app/model/ProductType.php');
require(__DIR__ . '/../../../app/model/Product.php');
require(__DIR__ . '/../../../app/model/DvdDisc.php');
require(__DIR__ . '/../../../app/model/Book.php');
require(__DIR__ . '/../../../app/model/Furniture.php');

$products = array(
    1 => new DvdDisc(),
    2 => new Book(),
    3 => new Furniture()
);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/product-web-app/src/resources/static/css/bootstrap.min.css">
    <title>Document</title>
</head>
