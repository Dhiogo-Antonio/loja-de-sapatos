<?php
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');
session_start();

if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header("Location: pedidos.php");
exit;
?>
