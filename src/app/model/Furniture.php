<?php

namespace App\Model;

use App\Util\ProductUtil;
use PDO;

class Furniture extends Product
{
    private const MAX_HEIGHT_SIZE = 10;
    private const MAX_WIDTH_SIZE = 10;
    private const MAX_LENGTH_SIZE = 10;
    private ?float $height;
    private ?float $width;
    private ?float $length;
    private string $heightErr = '';
    private string $widthErr = '';
    private string $lengthErr = '';

    public function __construct(
        int $id = 0,
        string $sku = '',
        string $name = '',
        float $price = 0.0,
        int $typeId = 0,
        float $height = null,
        float $width = null,
        float $length = null
    ) {
        parent::__construct($id, $name, $price, $sku, $typeId);
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    protected function insertSubProduct($productId)
    {
        $stmt = $this->conn->prepare("INSERT INTO furniture (product_id, height, width, length) VALUES (?, ?, ?, ?)");
        $stmt->execute([$productId, $this->height, $this->width, $this->length]);
    }

    public function displayProductAttributeInputFields()
    {
        echo "<div class='mb-3'>
                <div class='label-col'>
                    <label class='form-check-label' for='height'>Height (CM)</label>
                </div>
                <div class='input-col'>
                    <input type='number' class='form-control'
                     id='height' name='height' placeholder='Enter furniture height'>
                    <div class='invalid-feedback' id='heightErr'></div>
                </div>
              </div>";
        echo "<div class='mb-3'>
                <div class='label-col'>
                    <label class='form-check-label' for='width'>Width (CM)</label>
                </div>
                <div class='input-col'>
                    <input type='number' class='form-control'
                     id='width' name='width' placeholder='Enter furniture width'>
                    <div class='invalid-feedback' id='widthErr'></div> 
                </div>
              </div>";
        echo "<div class='mb-3'>
                <div class='label-col'>
                    <label class='form-check-label' for='length'>Length (CM)</label>
                </div>
                <div class='input-col'>
                    <input type='number' class='form-control'
                     id='length' name='length' placeholder='Enter furniture length'>
                    <div class='invalid-feedback' id='lengthErr'></div>
                    <br>
                    <div>Please, provide dimensions</div>
                </div>
              </div>";
    }

    public function validateProductAttributes()
    {
        if (empty($_POST['height'])) {
            $this->heightErr = 'Please, provide height data';
        } elseif (ProductUtil::isInvalidInput($_POST['height'])) {
            $this->heightErr = 'Please, provide the data of indicated type';
        } elseif (ProductUtil::countOfDigits($_POST['height']) > self::MAX_HEIGHT_SIZE) {
            $this->heightErr = 'Furniture height must be up to ' . self::MAX_HEIGHT_SIZE . ' digits!';
        }

        if (empty($_POST['width'])) {
            $this->widthErr = 'Please, provide width data';
        } elseif (ProductUtil::isInvalidInput($_POST['width'])) {
            $this->widthErr = 'Please, provide the data of indicated type';
        } elseif (ProductUtil::countOfDigits($_POST['width']) > self::MAX_WIDTH_SIZE) {
            $this->widthErr = 'Furniture width must be up to ' . self::MAX_WIDTH_SIZE . ' digits!';
        }

        if (empty($_POST['length'])) {
            $this->lengthErr = 'Please, provide length data';
        } elseif (ProductUtil::isInvalidInput($_POST['length'])) {
            $this->lengthErr = 'Please, provide the data of indicated type';
        } elseif (ProductUtil::countOfDigits($_POST['length']) > self::MAX_LENGTH_SIZE) {
            $this->lengthErr = 'Furniture length must be up to ' . self::MAX_LENGTH_SIZE . ' digits!';
        }

        if (empty($this->heightErr) && empty($this->widthErr) && empty($this->lengthErr)) {
            $this->height = filter_input(INPUT_POST, 'height', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $this->width = filter_input(INPUT_POST, 'width', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $this->length = filter_input(INPUT_POST, 'length', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }

    public function getProductAttributeFieldsErrors(): string
    {
        return $this->getHeightErr() . $this->getWidthErr() . $this->getLengthErr();
    }

    public function getProductAttributeFieldsErrorArray(): array
    {
        return array($this->heightErr, $this->widthErr, $this->lengthErr);
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
        $product->height = str_replace('.00', '', $product->height);
        $product->width = str_replace('.00', '', $product->width);
        $product->length = str_replace('.00', '', $product->length);
        echo '<p class="attribute">Dimension: ' . $product->height . 'x' . $product->width . 'x' . $product->length . '</p>';
    }

    public function deleteProduct($productId)
    {
        $stmt = $this->conn->prepare(
            "
        DELETE product, f FROM product 
        INNER JOIN furniture f on product.product_id = f.product_id
        WHERE f.product_id = ?;
        "
        );
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

    public function getHeightErr(): string
    {
        return $this->heightErr;
    }

    public function getWidthErr(): string
    {
        return $this->widthErr;
    }

    public function getLengthErr(): string
    {
        return $this->lengthErr;
    }
}