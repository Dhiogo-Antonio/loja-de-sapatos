<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

// Verifica se é admin
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ====== ATUALIZAR STATUS ======
if (isset($_POST['editar_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
}

// ====== EXCLUIR PEDIDO ======
if (isset($_POST['excluir_pedido'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
    $stmt->execute([$id]);
}

// ====== BUSCAR PEDIDOS (com JOIN) ======
$sql = "SELECT 
            p.id, 
            u.nome AS usuario_nome, 
            pr.nome AS produto_nome, 
            p.quantidade, 
            p.subtotal, 
            p.total, 
            p.status, 
            p.endereco_entrega,
            p.metodo_pagamento,
            p.data_pedido
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        JOIN produtos pr ON p.produto_id = pr.id
        ORDER BY p.data_pedido DESC";

$stmt = $pdo->query($sql);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerenciar Pedidos</title>
<link rel="stylesheet" href="admin.css">
<style>
/* ====== Estilo das Tabelas ====== */
.table-container {
  background: #fff;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  margin-top: 30px;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
  font-size: 15px;
}

th {
  background: #000;
  color: white;
  text-align: left;
  padding: 12px 15px;
  border-bottom: 3px solid #000;
}

td {
  padding: 12px 15px;
  border-bottom: 1px solid #ddd;
  color: #333;
  vertical-align: middle;
}

tr:hover {
  background: #f8f8f8;
}

td button {
  background: #000;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 6px 12px;
  text-decoration: none;
  cursor: pointer;
  font-size: 14px;
  transition: 0.3s;
}

td button:hover {
  background: #333;
}

form select {
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 5px;
  font-size: 14px;
}

/* ====== COLUNAS DE TEXTO LONGO ====== */
td:nth-child(7),
td:nth-child(8) {
  max-width: 220px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Tooltip de texto completo */
td:nth-child(7):hover,
td:nth-child(8):hover {
  white-space: normal;
  overflow: visible;
  background: #f9f9f9;
  position: relative;
  z-index: 2;
  border-radius: 5px;
}

/* ====== STATUS COLORIDO ====== */
select[name="status"] {
  border-radius: 6px;
  padding: 6px 10px;
  font-weight: 600;
  color: #000;
}



</style>
</head>

<body>
<div class="admin-container">
  <aside class="sidebar">
     <img src="../frontend/img/logo.jpg" alt="logo" width="100" class="img-logo">
    <h2>🛒 RD Modas</h2>
    <nav>
      <a href="index.php">📊 Dashboard</a>
      <a href="usuarios.php">👥 Usuários</a>
      <a href="produtos.php">👟 Produtos</a>
      <a href="pedidos.php" class="active">📦 Pedidos</a>
      <a href="logout.php" class="logout">🚪 Sair</a>
    </nav>
  </aside>

  <main class="content">
    <h1>Gerenciar Pedidos</h1>

    <div class="table-container">
      <table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Produto</th>
      <th>Quantidade</th>
      <th>Subtotal (R$)</th>
      <th>Total (R$)</th>
      <th>Endereço de Entrega</th>
      <th>Método de Pagamento</th>
      <th>Status</th>
      <th>Data</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($pedidos as $p): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['usuario_nome']) ?></td>
      <td><?= htmlspecialchars($p['produto_nome']) ?></td>
      <td><?= $p['quantidade'] ?></td>
      <td><?= number_format($p['subtotal'], 2, ',', '.') ?></td>
      <td><?= number_format($p['total'], 2, ',', '.') ?></td>
      <td><?= htmlspecialchars($p['endereco_entrega'] ?? '-') ?></td>
      <td><?= htmlspecialchars($p['metodo_pagamento'] ?? '-') ?></td>
      <td>
        <form method="POST" style="display:flex; gap:5px; align-items:center;">
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <select name="status">
            <option value="Pendente" <?= $p['status'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
            <option value="Pago" <?= $p['status'] == 'Pago' ? 'selected' : '' ?>>Pago</option>
            <option value="Cancelado" <?= $p['status'] == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
          </select>
          <button type="submit" name="editar_status">Salvar</button>
        </form>
      </td>
      <td><?= date('d/m/Y H:i', strtotime($p['data_pedido'])) ?></td>
      <td>
        <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este pedido?')">
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <button type="submit" name="excluir_pedido">Excluir</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
    </div>
  </main>
</div>
</body>
</html>
