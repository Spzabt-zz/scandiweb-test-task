<?php

abstract class Product
{
    protected PDO $conn;
    private int $id;
    private string $sku;
    private string $name;
    private float $price;
    private int $typeId;

    public function __construct(int $id = 0, string $name = '', float $price = 0.0, string $sku = '', int $typeId = 0)
    {
        $this->conn = DbConnection::getInstance()->getConnection();
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->typeId = $typeId;
    }

    abstract protected function insertSubProduct($productId);

    abstract public function deleteProduct($productId);

    abstract public function displayProductAttributeInputFields();

    abstract public function getProductList();

    abstract public function displayProductAttributes($product);

    abstract public function validateProductAttributes();

    public function saveProduct($productTypeId): bool
    {
        $productFromDb = $this->conn->prepare("SELECT Sku FROM product WHERE Sku = ?");
        $productFromDb->execute([$this->sku]);
        $productFromDb->setFetchMode(PDO::FETCH_OBJ);
        $productFromDb = $productFromDb->fetchAll();

        if (count($productFromDb) !== 0) {
            return false;
        }

        $insertProductStatement = $this->conn->prepare(
            "INSERT INTO product (Sku, name, price, type_id) VALUES (?, ?, ?, ?)"
        );
        $insertProductStatement->execute([$this->sku, $this->name, $this->price, $productTypeId]);

        $productId = $this->conn->lastInsertId();
        $productId = intval($productId);

        $this->insertSubProduct($productId);

        return true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function setTypeId(int $typeId): void
    {
        $this->typeId = $typeId;
    }
}