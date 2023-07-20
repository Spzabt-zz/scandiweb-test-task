<?php

namespace App\Model;

use App\Util\ProductUtil;
use PDO;

class DvdDisc extends Product
{
    private const MAX_SIZE_LENGTH = 10;
    private ?float $size;
    private string $sizeErr = '';

    public function __construct(
        int $id = 0,
        string $sku = '',
        string $name = '',
        float $price = 0.0,
        int $typeId = 0,
        float $size = null
    ) {
        parent::__construct($id, $name, $price, $sku, $typeId);
        $this->size = $size;
    }

    protected function insertSubProduct($productId)
    {
        $stmt = $this->conn->prepare("INSERT INTO dvd_disc (product_id, size) VALUES (?, ?)");
        $stmt->execute([$productId, $this->size]);
    }

    public function displayProductAttributeInputFields()
    {
        echo '<div class="mb-3">
                <div class="label-col">
                    <label class="form-check-label" for="size">Size (MB)</label>
                </div>
                <div class="input-col">
                    <input type="number" step="any" class="form-control"
                        id="size" name="size" placeholder="Enter DVD size">
                    <div class="invalid-feedback" id="sizeErr"></div>
                    <br>
                    <div>Please, provide size</div>
                </div>
              </div>';
    }

    public function validateProductAttributes()
    {
        if (empty($_POST['size'])) {
            $this->sizeErr = 'Please, provide size data';
        } elseif (ProductUtil::isInvalidInput($_POST['size'])) {
            $this->sizeErr = 'Please, provide the data of indicated type';
        } elseif (ProductUtil::countOfDigits($_POST['size']) > self::MAX_SIZE_LENGTH) {
            $this->sizeErr = 'DVD size must be up to ' . self::MAX_SIZE_LENGTH . ' digits!';
        } else {
            $this->size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }

    public function getProductAttributeFieldsErrors(): string
    {
        return $this->getSizeErr();
    }

    public function getProductAttributeFieldsErrorArray(): array
    {
        return array($this->sizeErr);
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
        $product->size = str_replace('.00', '', $product->size);
        echo '<p class="attribute">Size: ' . $product->size . ' MB</p>';
    }

    public function deleteProduct($productId)
    {
        $stmt = $this->conn->prepare(
            "
        DELETE product, dd FROM product 
        INNER JOIN dvd_disc dd on product.product_id = dd.product_id
        WHERE dd.product_id = ?;
        "
        );
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

    public function getSizeErr(): string
    {
        return $this->sizeErr;
    }
}