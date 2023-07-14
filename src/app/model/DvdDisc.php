<?php

class DvdDisc extends Product
{
    private float $size;

    public function __construct(
        int $id = 0,
        string $sku = '',
        string $name = '',
        float $price = 0.0,
        int $typeId = 0,
        float $size = 0.0
    ) {
        parent::__construct($id, $name, $price, $sku, $typeId);
        $this->size = $size;
    }

    protected function insertSubProduct($productId)
    {
//        if (empty($_POST['name'])) {
//            $sizeErr = 'Please, submit required data';
//        } else {
//            $this->size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        }

        $stmt = $this->conn->prepare("INSERT INTO dvd_disc (product_id, size) VALUES (?, ?)");
        $stmt->execute([$productId, $this->size]);
    }

    public function displayProductAttributeInputFields()
    {
        //$this->size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        /*echo '<label class="form-check-label" for="productPrice">price</label>
            <input type="number" step="any" class="form-label <?php echo $priceErr ? 'is-invalid' : ''; ?>"
                   id="productPrice" name="price" value="<?php echo $price; ?>" placeholder="Enter product price">
            <div class="invalid-feedback">
                <?php echo $priceErr ?>
            </div>';*/

        echo '<div class="mb-3 form-check">
                <label class="form-check-label" for="size">size</label>
                <input type="number" class="form-label" id="size" name="size">
              </div>';
//        echo "<div class='invalid-feedback'>
//                $this->size
//              </div>";
        echo '<div>Please, provide size</div>';
    }

    public function validateProductAttributes()
    {

    }

    public function getProductList()
    {
        $sql = "SELECT product.product_id, product.Sku, product.name, product.price, product.type_id, dd.size
                FROM product 
                INNER JOIN dvd_disc dd ON product.product_id = dd.product_id
                ORDER BY product.product_id";
        $stmt = $this->conn->query($sql);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        return $stmt->fetchAll();
    }

    public function displayProductAttributes($product)
    {
        echo '<p class="card-text">Size: ' . $product->size . ' MB</p>';
    }

    public function deleteProduct($productId)
    {
        $stmt = $this->conn->prepare("
        DELETE product, dd FROM product 
        INNER JOIN dvd_disc dd on product.product_id = dd.product_id
        WHERE dd.product_id = ?;
        ");
        $stmt->execute([$productId]);
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function setSize(float $size): void
    {
        $this->size = $size;
    }
}