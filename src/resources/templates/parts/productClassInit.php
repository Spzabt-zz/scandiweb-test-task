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
