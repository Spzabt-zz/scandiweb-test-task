<?php

class Book extends Product
{
    private float $weight;

    public function __construct(int $id = 0, string $sku = '', string $name = '', float $price = 0.0, int $typeId = 0, float $weight = 0.0)
    {
        parent::__construct($id, $name, $price, $sku, $typeId);
        $this->weight = $weight;
    }

    protected function insertSubProduct($productId)
    {
        $this->weight = filter_input(INPUT_POST, 'weight', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $stmt = $this->conn->prepare("INSERT INTO book (product_id, weight) VALUES (?, ?)");
        $stmt->execute([$productId, $this->weight]);
    }

    public function displayProductAttributeInputFields()
    {
        echo '<div class="mb-3 form-check">
                <label class="form-check-label" for="weight">weight</label>
                <input type="number" class="form-label" id="weight" name="weight">
              </div>';
        echo '<div>Please, provide weight</div>';
    }

    public function getProductList()
    {
        $sql = "SELECT product.product_id, product.Sku, product.name, product.price, product.type_id, b.weight
                FROM product 
                INNER JOIN book b ON product.product_id = b.product_id
                ORDER BY product.product_id";
        $stmt = $this->conn->query($sql);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        return $stmt->fetchAll();
    }

    public function displayProductAttributes($product)
    {
        echo '<p class="card-text">Weight: ' . $product->weight . 'KG</p>';
    }

    public function deleteProduct($productId)
    {
        $stmt = $this->conn->prepare("
        DELETE product, b FROM product 
        INNER JOIN book b on product.product_id = b.product_id
        WHERE b.product_id = ?;
        ");
        $stmt->execute([$productId]);
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }
}