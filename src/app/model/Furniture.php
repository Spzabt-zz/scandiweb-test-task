<?php

class Furniture extends Product
{
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
        echo "<div class='mb-3 form-check'>
                <label class='form-check-label' for='height'>height</label>
                <input type='number' class='form-label'
                 id='height' name='height' placeholder='Enter furniture height'>
                <div class='invalid-feedback' id='heightErr'></div>
              </div>";
        echo "<div class='mb-3 form-check'>
                <label class='form-check-label' for='width'>width</label>
                <input type='number' class='form-label'
                 id='width' name='width' placeholder='Enter furniture width'>
                <div class='invalid-feedback' id='widthErr'></div> 
              </div>";
        echo "<div class='mb-3 form-check'>
                <label class='form-check-label' for='length'>length</label>
                <input type='number' class='form-label'
                 id='length' name='length' placeholder='Enter furniture length'>
                <div class='invalid-feedback' id='lengthErr'></div>
              </div>";
        echo '<div>Please, provide dimensions</div>';
    }

    public function validateProductAttributes()
    {
        if (empty($_POST['height'])) {
            $this->heightErr = 'Please, provide height data';
        }
        if (empty($_POST['width'])) {
            $this->widthErr = 'Please, provide width data';
        }
        if (empty($_POST['length'])) {
            $this->lengthErr = 'Please, provide length data';
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
        echo '<p class="card-text">Dimension: ' . $product->height . 'x' . $product->width . 'x' . $product->length . '</p>';
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