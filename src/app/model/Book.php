<?php

namespace App\Model;

use App\Util\ProductUtil;
use PDO;

class Book extends Product
{
    private const MAX_WEIGHT_LENGTH = 10;
    private ?float $weight;
    private string $weightErr = '';

    public function __construct(
        int $id = 0,
        string $sku = '',
        string $name = '',
        float $price = 0.0,
        int $typeId = 0,
        float $weight = null
    ) {
        parent::__construct($id, $name, $price, $sku, $typeId);
        $this->weight = $weight;
    }

    protected function insertSubProduct($productId)
    {
        $stmt = $this->conn->prepare("INSERT INTO book (product_id, weight) VALUES (?, ?)");
        $stmt->execute([$productId, $this->weight]);
    }

    public function displayProductAttributeInputFields()
    {
        echo "<div class='mb-3'>
                <div class='label-col'>
                    <label class='form-check-label' for='weight'>Weight (KG)</label>
                </div>
                <div class='input-col'>
                    <input type='number' class='form-control'
                     id='weight' name='weight' placeholder='Enter book weight'>
                    <div class='invalid-feedback' id='weightErr'></div>
                    <br>
                    <div>Please, provide weight</div>
                </div>
              </div>";
    }

    public function validateProductAttributes()
    {
        if (empty($_POST['weight'])) {
            $this->weightErr = 'Please, provide weight data';
        } elseif (ProductUtil::isInvalidInput($_POST['weight'])) {
            $this->weightErr = 'Please, provide the data of indicated type';
        } elseif (ProductUtil::countOfDigits($_POST['weight']) > self::MAX_WEIGHT_LENGTH) {
            $this->weightErr = 'Book weight must be up to ' . self::MAX_WEIGHT_LENGTH . ' digits!';
        } else {
            $this->weight = filter_input(INPUT_POST, 'weight', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }

    public function getProductAttributeFieldsErrors(): string
    {
        return $this->getWeightErr();
    }

    public function getProductAttributeFieldsErrorArray(): array
    {
        return array($this->weightErr);
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
        $product->weight = str_replace('.00', '', $product->weight);
        echo '<p class="attribute">Weight: ' . $product->weight . 'KG</p>';
    }

    public function deleteProduct($productId)
    {
        $stmt = $this->conn->prepare(
            "
        DELETE product, b FROM product 
        INNER JOIN book b on product.product_id = b.product_id
        WHERE b.product_id = ?;
        "
        );
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

    public function getWeightErr(): string
    {
        return $this->weightErr;
    }
}