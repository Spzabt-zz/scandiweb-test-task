<?php

use App\Model\ProductType;

require_once(__DIR__ . '/parts/header.php');

?>

<script src="/product-web-app/src/resources/static/js/fieldValidator.js"></script>
<script src="/product-web-app/src/resources/static/js/typeSwitcher.js"></script>

<body>
<form id="product_form" method="POST" action="<?php
echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

    <div class="test-task-header">
        <h1 class="page-logo">Product Add</h1>
        <div class="header-controls">
            <input class="product-save-btn" id="submit" type="submit" name="submit" value="Save"
                   onclick="validateProductFormInputFields(event);">
            <input class="cancel-btn" type="button" onclick="location.href='/product-web-app/';"
                   value="Cancel"/>
        </div>
    </div>

    <div class="form-input-container">
        <div class="mb-3">
            <div class="label-col">
                <label for="sku" class="form-label">SKU</label>
            </div>
            <div class="input-col">
                <input type="text" class="form-control"
                       id="sku" name="sku" placeholder="Enter SKU">
                <div class="invalid-feedback" id="sku_error"></div>
            </div>
        </div>
        <div class="mb-3">
            <div class="label-col">
                <label for="name" class="form-label">Name</label>
            </div>
            <div class="input-col">
                <input type="text" class="form-control"
                       id="name" name="name" placeholder="Enter product name">
                <div class="invalid-feedback" id="name_error"></div>
            </div>
        </div>
        <div class="mb-3">
            <div class="label-col">
                <label class="form-check-label" for="price">Price ($)</label>
            </div>
            <div class="input-col">
                <input type="number" step="any" class="form-control"
                       id="price" name="price" placeholder="Enter product price">
                <div class="invalid-feedback" id="price_error"></div>
            </div>
        </div>
        <div class="mb-3">
            <div class="label-col">
                <label class="form-check-label" for="productType">Type Switcher</label>
            </div>
            <div class="input-col">
                <select class="form-control" id="productType" aria-label="Type Switcher" name="typeSwitcher"
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
                <span id="productTypeError" class="invalid-feedback"></span>
            </div>
        </div>

        <div id="productAttr"></div>
    </div>
</form>

<?php
require_once(__DIR__ . '/parts/footer.php'); ?>
