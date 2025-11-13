<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Contadores
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalProdutos = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel Administrativo</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-container">
  <aside class="sidebar">
    <h2>🛒 RD Modas</h2>
    <nav>
      <a href="index.php" class="active">📊 Dashboard</a>
      <a href="usuarios.php">👥 Usuários</a>
      <a href="produtos.php">👟 Produtos</a>
      <a href="pedidos.php">📦 Pedidos</a>
      <a href="logout.php" class="logout">🚪 Sair</a>
    </nav>
  </aside>

  <main class="content">
    <h1>Painel de Administração</h1>
    <div class="cards">
      <div class="card">
        <h2><?= $totalUsuarios ?></h2>
        <p>Usuários</p>
      </div>
      <div class="card">
        <h2><?= $totalProdutos ?></h2>
        <p>Produtos</p>

        
