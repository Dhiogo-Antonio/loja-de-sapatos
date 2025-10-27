<?php
require_once __DIR__ . '/../config/database.php';

class ProductController {
    public function listAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        global $pdo;
        $sql = "INSERT INTO products (name, price, description, image, stock)
                VALUES (:name, :price, :description, :image, :stock)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
