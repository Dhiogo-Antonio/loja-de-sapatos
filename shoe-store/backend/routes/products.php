<?php
require_once '../controllers/ProductController.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$controller = new ProductController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($controller->listAll());
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode(['success' => $controller->create($data)]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) $controller->delete($id);
    echo json_encode(['success' => true]);
}
