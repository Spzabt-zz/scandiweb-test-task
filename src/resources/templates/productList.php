<?php require_once(__DIR__ . '/parts/header.php'); ?>

<body>
<form id="massDeleteForm" method="POST" action="/product-web-app/remove-product">

    <button class="btn btn-primary" type="button" onclick="location.href='/product-web-app/add-product';">ADD</button>

    <button class="btn btn-outline-success" id="delete-product-btn" type="submit" name="massDeleteSubmit">MASS DELETE</button>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Product List</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


        </div>
    </nav>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        $products = $productController->getProducts();

        if (count($productsFromDb) !== 0) {
            foreach ($productsFromDb as $p) {
                echo '<div class="col">';
                echo '<div class="card h-100">';
                echo '<div class="form-check">';
                echo '<input class="delete-checkbox" type="checkbox" name="productIds[]" value="' . $p->product_id . '">';
                echo '</div>';
                echo '<p class="card-text">' . $p->Sku . '</p>';
                echo '<p class="card-text">' . $p->name . '</p>';
                echo '<p class="card-text">' . $p->price . ' $</p>';
                $products[$p->type_id]->displayProductAttributes($p);
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</form>

<script src="/product-web-app/src/resources/static/js/bootstrap.min.js"></script>
</body>
</html>