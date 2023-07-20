<?php
require_once(__DIR__ . '/parts/header.php'); ?>

<body>
<form id="massDeleteForm" method="POST" action="/product-web-app/remove-product">

    <div class="test-task-header">
        <h1 class="page-logo">Product List</h1>
        <div class="header-controls">
            <button class="add-product-btn" type="button" onclick="location.href='/product-web-app/add-product';">
                ADD
            </button>
            <button class="mass-delete-product-btn" id="delete-product-btn" type="submit" name="massDeleteSubmit">
                MASS DELETE
            </button>
        </div>
    </div>

    <div class="product-list">
        <?php
        $products = $productController->getProducts();

        if (count($productsFromDb) !== 0) {
            foreach ($productsFromDb as $p) {
                echo '<div class="product-info">';
                echo '<input class="delete-checkbox" type="checkbox" name="productIds[]" value="' . $p->product_id . '">';
                echo '<p class="attribute">' . $p->Sku . '</p>';
                echo '<p class="attribute">' . $p->name . '</p>';
                echo '<p class="attribute">' . $p->price . ' $</p>';
                $products[$p->type_id]->displayProductAttributes($p);
                echo '</div>';
            }
        } else {
            echo '<p>Product list is empty! Add some products</p>';
        }
        ?>
    </div>
</form>

<?php
require_once(__DIR__ . '/parts/footer.php'); ?>
