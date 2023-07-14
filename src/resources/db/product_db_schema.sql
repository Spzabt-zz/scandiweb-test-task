CREATE TABLE Product_Type
(
    type_id   INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    type_name VARCHAR(255) NOT NULL
);

CREATE TABLE Product
(
    product_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Sku        VARCHAR(255) NOT NULL UNIQUE,
    name       VARCHAR(255) NOT NULL,
    price      DECIMAL(10, 2) NOT NULL,
    type_id    INT NOT NULL,
    FOREIGN KEY (type_id) REFERENCES Product_Type (type_id)
);

CREATE TABLE DVD_Disc
(
    product_id INT PRIMARY KEY NOT NULL,
    size       DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES Product (product_id)
);

CREATE TABLE Book
(
    product_id INT PRIMARY KEY NOT NULL,
    weight     DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES Product (product_id)
);

CREATE TABLE Furniture
(
    product_id INT PRIMARY KEY NOT NULL,
    height     DECIMAL(10, 2) NOT NULL,
    width      DECIMAL(10, 2) NOT NULL,
    length     DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES Product (product_id)
);

