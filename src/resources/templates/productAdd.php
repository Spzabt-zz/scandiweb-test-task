<?php

require_once(__DIR__ . '/parts/header.php');

$sku = $name = $price = '';
$skuErr = $nameErr = $priceErr = '';

$productType = new ProductType();
$pTypes = $productType->getProductTypes();

if (isset($_POST['submit'])) {
    if (empty($_POST['sku'])) {
        $skuErr = 'Please, submit required data';
    } else {
        $sku = filter_input(INPUT_POST, 'sku', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if (empty($_POST['name'])) {
        $nameErr = 'Please, submit required data';
    } else {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if (empty($_POST['price'])) {
        $priceErr = 'Please, submit required data';
    } else {
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

/*    if (empty($_POST['price'])) {
        $skuErr = 'Please, submit required data';
    } else {
        $typeId = $_POST['typeSwitcher'];
        $productTypeId = intval($typeId);
    }*/

    $typeId = $_POST['typeSwitcher'];
    $productTypeId = intval($typeId);

    if (empty($skuErr) && empty($nameErr) && empty($priceErr)) {
        if (isset($products[$productTypeId])) {
            $product = $products[$productTypeId];
            $product->setSku($sku);
            $product->setName($name);
            $product->setPrice($price);
            if (!$product->saveProduct($productTypeId)) {
                $skuErr = 'Please, provide unique SKU. Product with provided SKU already exist!';
            } else {
                header("Location: productList.php");
                exit;
            }
        }
    }
}
?>

<script src="../static/js/typeSwitcher.js"></script>
<body>
<div class="container">
    <form id="product_form" method="POST" action="<?php
    echo htmlspecialchars(
        $_SERVER['PHP_SELF']
    ); ?>">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Product Add</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <a href="productList.php">Cancel</a>
                </div>
            </div>
        </nav>

        <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" class="form-control <?php echo $skuErr ? 'is-invalid' : ''; ?>"
                   id="sku" name="sku" value="<?php echo $sku; ?>" placeholder="Enter SKU">
            <div class="invalid-feedback">
                <?php echo $skuErr ?>
            </div>
        </div>
        <div class="mb-3">
            <label for="productName" class="form-label">name</label>
            <input type="text" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>"
                   id="productName" name="name" value="<?php echo $name; ?>" placeholder="Enter product name">
            <div class="invalid-feedback">
                <?php echo $nameErr ?>
            </div>
        </div>
        <div class="mb-3 form-check">
            <label class="form-check-label" for="productPrice">price</label>
            <input type="number" step="any" class="form-label <?php echo $priceErr ? 'is-invalid' : ''; ?>"
                   id="productPrice" name="price" value="<?php echo $price; ?>" placeholder="Enter product price">
            <div class="invalid-feedback">
                <?php echo $priceErr ?>
            </div>
        </div>
        <select class="form-select" aria-label="Type Switcher" name="typeSwitcher"
                onchange="switchProductType(this.value)">
            <option selected>Type Switcher</option>
            <?php
            foreach ($pTypes as $type) {
                echo "<option value = \"$type->type_id\" >$type->type_name</option>";
            }
            ?>
        </select>

        <div id="productAttr"></div>
        <!--        <div class="mb-3 form-check">-->
        <!--            <label class="form-check-label" for="productAttr">size</label>-->
        <!--            <input type="number" class="form-label" id="productAttr" name="size">-->
        <!--        </div>-->

        <input class="btn btn-outline-success" type="submit" name="submit" value="save">

    </form>
</div>
<script src="../static/js/bootstrap.min.js"></script>
</body>
</html>