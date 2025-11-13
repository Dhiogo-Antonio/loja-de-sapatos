<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['user_id'];

// 🔹 Excluir pedido
if (isset($_GET['excluir'])) {
    $pedido_id = (int)$_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->execute([':id' => $pedido_id, ':usuario_id' => $usuario_id]);
    header("Location: historico.php");
    exit;
}

// 🔹 Buscar pedidos
$stmt = $pdo->prepare("
    SELECT p.id, pr.nome AS produto_nome, pr.imagem, p.quantidade, p.subtotal, p.total, p.status, p.data_pedido
    FROM pedidos p
    JOIN produtos pr ON p.produto_id = pr.id
    WHERE p.usuario_id = :usuario_id
    ORDER BY p.data_pedido DESC
");
$stmt->execute([':usuario_id' => $usuario_id]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Histórico de Compras</title>
<link rel="stylesheet" href="css/historico.css">


</head>
<body>

<header>
  <img src="img/logo.jpg" alt="logo" width="100">
  
</header>
<h1>Histórico de Compras</h1>
<hr>
<main>
  <?php if (empty($pedidos)): ?>
    <p style="text-align:center; font-size:1.2rem;">🛍️ Você ainda não fez nenhuma compra.</p>
    <div style="text-align:center;">
    </div>
  <?php else: ?>
  <div class="historico-container">
    <?php foreach ($pedidos as $pedido): ?>
      <div class="card-pedido">
        <div class="card-top">
            Pedido
          <button class="btn-excluir" onclick="confirmarExclusao(<?= $pedido['id'] ?>)">×</button>
        </div>
        <div class="card-body">
          <img src="<?= htmlspecialchars($pedido['imagem']) ?>" alt="<?= htmlspecialchars($pedido['produto_nome']) ?>">
          <div class="card-info">
            <p><strong>Produto:</strong> <?= htmlspecialchars($pedido['produto_nome']) ?></p>
            <p><strong>Quantidade:</strong> <?= htmlspecialchars($pedido['quantidade']) ?></p>
            <p><strong>Subtotal:</strong> R$ <?= number_format($pedido['subtotal'], 2, ',', '.') ?></p>
            <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>
            <p><strong>Status:</strong> <span class="status <?= htmlspecialchars($pedido['status']) ?>"><?= htmlspecialchars($pedido['status']) ?></span></p>
          </div>
          <div class="card-footer">
            <span class="data">🗓 <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <a href="index.php" class="voltar">← Voltar para a Loja</a>
</main>

<footer>
  &copy; 2025 RD Modas — Todos os direitos reservados.
</footer>

</body>
</html>
<script>
function confirmarExclusao(id) {
  
    window.location.href = "historico.php?excluir=" + id;
  
}
</script>