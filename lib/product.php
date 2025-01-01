<?php
require_once __DIR__ . '/../config/db.php';

function fetchAllProducts($conn) {
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchCategoryCounts($conn) {
    $stmt = $conn->prepare("SELECT category, COUNT(*) AS count FROM products GROUP BY category");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addProduct($conn, $name, $price, $category) {
    $stmt = $conn->prepare("INSERT INTO products (name, price, category) VALUES (:name, :price, :category)");
    return $stmt->execute(['name' => $name, 'price' => $price, 'category' => $category]);
}

function updateProduct($conn, $id, $name, $price, $category) {
    $stmt = $conn->prepare("UPDATE products SET name = :name, price = :price, category = :category WHERE id = :id");
    return $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price, 'category' => $category]);
}

function deleteProduct($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>
