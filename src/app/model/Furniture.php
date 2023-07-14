<?php

class Furniture extends Product
{
    private float $height;
    private float $width;
    private float $length;

    public function __construct(
        int $id = 0,
        string $sku = '',
        string $name = '',
        float $price = 0.0,
        int $typeId = 0,
        float $height = 0.0,
        float $width = 0.0,
        float $length = 0.0
    ) {
        parent::__construct($id, $name, $price, $sku, $typeId);
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    protected function insertSubProduct($productId)
    {
        $this->height = filter_input(INPUT_POST, 'height', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->width = filter_input(INPUT_POST, 'width', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->length = filter_input(INPUT_POST, 'length', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $stmt = $this->conn->prepare("INSERT INTO furniture (product_id, height, width, length) VALUES (?, ?, ?, ?)");
        $stmt->execute([$productId, $this->height, $this->width, $this->length]);
    }

    public function displayProductAttributeInputFields()
    {
        echo '<div class="mb-3 form-check">
                <label class="form-check-label" for="height">height</label>
                <input type="number" class="form-label" id="height" name="height">
              </div>';
        echo '<div class="mb-3 form-check">
                <label class="form-check-label" for="width">width</label>
                <input type="number" class="form-label" id="width" name="width">
              </div>';
        echo '<div class="mb-3 form-check">
                <label class="form-check-label" for="length">length</label>
                <input type="number" class="form-label" id="length" name="length">
              </div>';
        echo '<div>Please, provide dimensions</div>';
    }

    public function getProductList()
    {
        $sql = "SELECT product.product_id, product.Sku, product.name, product.price, product.type_id, f.height, f.width, f.length
                FROM product 
                INNER JOIN furniture f ON product.product_id = f.product_id
                ORDER BY product.product_id";
        $stmt = $this->conn->query($sql);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        return $stmt->fetchAll();
    }

    public function displayProductAttributes($product)
    {
        echo '<p class="card-text">Dimension: ' . $product->height . 'x' . $product->width . 'x' . $product->length . '</p>';
    }

    public function deleteProduct($productId)
    {
        $stmt = $this->conn->prepare("
        DELETE product, f FROM product 
        INNER JOIN furniture f on product.product_id = f.product_id
        WHERE f.product_id = ?;
        ");
        $stmt->execute([$productId]);
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function setLength(float $length): void
    {
        $this->length = $length;
    }
}