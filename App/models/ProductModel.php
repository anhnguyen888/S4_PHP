<?php

namespace App\Models;

use PDO;

class ProductModel
{

    private $conn;
    private $table_name = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function getProducts()
    {
        $query = "SELECT p.id, p.title, p.description, p.image, c.name as category_name 
          FROM " . $this->table_name . " AS p 
          LEFT JOIN category AS c ON p.categoryid = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function getProductById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    public function addProduct($name, $description, $category_id, $image)
    {

        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (count($errors) > 0) {
            return $errors;
        }


        $query = "INSERT INTO " . $this->table_name . " (title, description,
        categoryid, image) VALUES (:name, :description, :categoryid, :image)";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categoryid', $category_id);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    public function updateProduct($id, $name, $description, $price, $category_id, $image)
    {

        $query = "UPDATE " . $this->table_name . " SET name=:name,
        description=:description, price=:price, category_id=:category_id, image=:image WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
