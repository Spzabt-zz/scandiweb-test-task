<?php

require_once(__DIR__ . '/parts/header.php');
require_once(__DIR__ . '/parts/productClassInit.php');
require(__DIR__ . '/../../app/util/ProductUtil.php');

if (isset($_POST['massDeleteSubmit']) && isset($_POST['productIds'])) {
    $productIds = $_POST['productIds'];

    foreach ($productIds as $productId) {
        foreach ($products as $product => $productValue) {
            $productValue->deleteProduct($productId);
        }
    }
}
?>


<body>
<form id="massDeleteForm" method="POST" action="<?php
echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Product List</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <input class="btn btn-primary" type="button" onclick="location.href='productAdd.php';" value="ADD" />

            <button class="btn btn-outline-success" id="delete-product-btn" type="submit" name="massDeleteSubmit">MASS DELETE</button>
        </div>
    </nav>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php

        $productsFromDb = [];
        foreach ($products as $product => $productValue) {
            $productsFromDb = array_merge($productsFromDb, $productValue->getProductList());
        }
        usort($productsFromDb, "ProductUtil::compareProducts");
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

<script src="../static/js/bootstrap.min.js"></script>
</body>
</html>