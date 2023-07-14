<?php

class DbConnection
{
    private static ?DbConnection $instance = null;
    private string $host;
    private string $username;
    private string $password;
    private string $dbName;
    private PDO $conn;

    private function __construct()
    {
        $this->host = "localhost";
        $this->username = "root";
        $this->password = "abc";
        $this->dbName = "product_db";

        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected!";
        } catch (PDOException $exception) {
            echo "Connection failed: " . $exception->getMessage();
        }
    }

    public static function getInstance(): DbConnection
    {
        if (self::$instance === null) {
            self::$instance = new DbConnection();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}
