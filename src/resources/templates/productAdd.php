<?php

require_once(__DIR__ . '/parts/header.php');
require_once(__DIR__ . '/parts/productClassInit.php');

?>

<script src="../static/js/typeSwitcher.js"></script>

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
                    <input class="btn btn-primary" type="button" onclick="location.href='productList.php';" value="Cancel" />
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

<script>
    function validateProductFormInputFields(event) {
        event.preventDefault();

        let productForm = document.getElementById("product_form");
        let productFormData = new FormData(productForm);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.open('POST', 'handleValidation.php', true);
        xmlHttp.send(productFormData);
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
                console.log(xmlHttp.responseText);
                try {
                    let response = JSON.parse(xmlHttp.responseText);

                    if (response.success) {
                        console.log('success');
                        window.location.href = "productList.php";
                    } else {
                        console.log('not success');

                        if (response.errors.sku_error !== '') {
                            document.getElementById('sku').classList.add("is-invalid");
                            document.getElementById('sku_error').innerHTML = response.errors.sku_error;
                        } else {
                            document.getElementById('sku').classList.remove("is-invalid");
                            document.getElementById('sku_error').innerHTML = response.errors.sku_error;
                        }

                        if (response.errors.name_error !== '') {
                            document.getElementById('name').classList.add("is-invalid");
                            document.getElementById('name_error').innerHTML = response.errors.name_error;
                        } else {
                            document.getElementById('name').classList.remove("is-invalid");
                            document.getElementById('name_error').innerHTML = response.errors.name_error;
                        }

                        if (response.errors.price_error !== '') {
                            document.getElementById('price').classList.add("is-invalid");
                            document.getElementById('price_error').innerHTML = response.errors.price_error;
                        } else {
                            document.getElementById('price').classList.remove("is-invalid");
                            document.getElementById('price_error').innerHTML = response.errors.price_error;
                        }

                        if (response.errors.productTypeError !== undefined && response.errors.productTypeError !== '') {
                            document.getElementById('productTypeError').innerHTML = response.errors.productTypeError;
                        } else {
                            document.getElementById('productTypeError').innerHTML = '';
                        }

                        if (response.errorAttrs && Object.keys(response.errorAttrs).length > 0)
                            for (let error in response.errorAttrs) {
                                let inputId = error.replace("Err", "");
                                console.log(inputId);

                                if (response.errorAttrs[error] !== '') {
                                    document.getElementById(inputId).classList.add("is-invalid");
                                    document.getElementById(error).innerHTML = response.errorAttrs[error];
                                } else {
                                    document.getElementById(inputId).classList.remove("is-invalid");
                                    document.getElementById(error).innerHTML = response.errorAttrs[error];
                                }
                            }
                    }
                } catch (error) {
                    console.log('Error parsing JSON response:', error);
                }
            }
        };
    }
</script>
<script src="../static/js/bootstrap.min.js"></script>
</body>
</html>