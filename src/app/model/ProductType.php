<?php

namespace App\Model;

use PDO;
use PDOException;
use App\Config\DbConnection as DataBaseConnection;

class ProductType
{
    private int $id;
    private string $typeName;
    private PDO $conn;

    public function __construct()
    {
        $this->conn = DataBaseConnection::getInstance()->getConnection();
    }

    public function getProductTypes(): array
    {
        try {
            $stmt = $this->conn->query("SELECT * FROM product_type");
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): void
    {
        $this->typeName = $typeName;
    }
}