<?php

use App\Model\ProductType;

require_once(__DIR__ . '/parts/header.php');

?>

<script src="/product-web-app/src/resources/static/js/fieldValidator.js"></script>
<script src="/product-web-app/src/resources/static/js/typeSwitcher.js"></script>

<body>
<div class="container">
    <form id="product_form" method="POST" action="<?php
    echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Product Add</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <input class="btn btn-primary" type="button" onclick="location.href='/product-web-app/';" value="Cancel" />
                </div>
            </div>
        </nav>

        <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" class="form-control"
                   id="sku" name="sku" placeholder="Enter SKU">
            <div class="invalid-feedback" id="sku_error"></div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">name</label>
            <input type="text" class="form-control"
                   id="name" name="name" placeholder="Enter product name">
            <div class="invalid-feedback" id="name_error"></div>
        </div>
        <div class="mb-3 form-check">
            <label class="form-check-label" for="price">price</label>
            <input type="number" step="any" class="form-label"
                   id="price" name="price" placeholder="Enter product price">
            <div class="invalid-feedback" id="price_error"></div>
        </div>
        <div class="mb-3 form-check">
            <select class="form-select" id="productType" aria-label="Type Switcher" name="typeSwitcher"
                    onchange="switchProductType(this.value)">
                <option selected>Type Switcher</option>
                <?php
                $productType = new ProductType();
                $pTypes = $productType->getProductTypes();

                foreach ($pTypes as $type) {
                    echo "<option value = \"$type->type_id\" >$type->type_name</option>";
                }
                ?>
            </select>
            <span id="productTypeError" class="text-danger"></span>
        </div>

        <div id="productAttr"></div>

        <input class="btn btn-outline-success" id="submit" type="submit" name="submit" value="Save"
               onclick="validateProductFormInputFields(event);">
    </form>
</div>

<script src="/product-web-app/src/resources/static/js/bootstrap.min.js"></script>
</body>
</html>