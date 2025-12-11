<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


if (empty($_SESSION['cart'])) {
    header("Location: carrinho.php");
    exit;
}


$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id IN ($placeholders)");
$stmt->execute($ids);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);


$total = 0;
foreach ($produtos as $p) {
    $qtd = $_SESSION['cart'][$p['id']];
    $total += $p['preco'] * $qtd;
}


if (isset($_POST['finalizar'])) {
    $endereco = trim($_POST['endereco']);
    $pagamento = $_POST['pagamento'];
    $usuario_id = $_SESSION['user_id'];

    foreach ($produtos as $p) {
        $qtd = $_SESSION['cart'][$p['id']];
        $subtotal = $p['preco'] * $qtd;

        $stmt = $pdo->prepare("
            INSERT INTO pedidos (usuario_id, produto_id, quantidade, subtotal, total, status, endereco_entrega, metodo_pagamento, data_pedido)
            VALUES (?, ?, ?, ?, ?, 'Pendente', ?, ?, NOW())
        ");
        $stmt->execute([$usuario_id, $p['id'], $qtd, $subtotal, $total, $endereco, $pagamento]);
    }

    $_SESSION['cart'] = [];
    header("Location: historico.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Finalizar Compra | RD Modas</title>
<link rel="stylesheet" href="css/pagamento.css">
</head>
<body>

<header>
  <img src="img/logo.jpg" alt="logo" width="120">
</header>

<main class="pagamento-wrapper">
  <section class="resumo">
    <h2>Resumo da Compra</h2>
    <?php foreach ($produtos as $p): 
        $qtd = $_SESSION['cart'][$p['id']];
        $subtotal = $p['preco'] * $qtd;
    ?>
    <div class="item">
      <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
      <div>
        <h4><?= htmlspecialchars($p['nome']) ?></h4>
        <p>Quantidae: <?= $qtd ?> × R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>
        <strong>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
      </div>
    </div>
    <?php endforeach; ?>
    <div class="total">
      <h3>Total: <span>R$ <?= number_format($total, 2, ',', '.') ?></span></h3>
    </div>
  </section>

  <section class="pagamento">
    <h2>Preencha os Dados</h2>
    <form method="POST" class="form-pagamento">
      <label for="endereco">Endereço de Entrega</label>
      <textarea id="endereco" name="endereco" required placeholder="Rua, número, bairro, cidade, CEP"></textarea>

      <label for="pagamento">Método de Pagamento</label>
      <select id="pagamento" name="pagamento" required>
        <option value="">Selecione...</option>
        <option value="Cartão de Crédito">Cartão de Crédito</option>
        <option value="Cartão de Débito">Cartão de Débito</option>
        <option value="Pix">Pix</option>
        <option value="Boleto Bancário">Boleto Bancário</option>
      </select>

      <button type="submit" name="finalizar" class="btn-finalizar">Finalizar Pedido</button>
      <a href="carrinho.php" class="voltar">← Voltar ao Carrinho</a>
    </form>
  </section>
</main>

</body>
</html>
