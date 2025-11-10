<?php
session_start();

// Conexão com o banco
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

// Redireciona se não estiver logado
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); 
    exit;
}

if(isset($_SESSION['user_id'])){
    $cartJson = json_encode($_SESSION['cart']);
    $stmt = $pdo->prepare("UPDATE usuarios SET carrinho = :carrinho WHERE id = :id");
    $stmt->execute([':carrinho' => $cartJson, ':id' => $_SESSION['user_id']]);
}


// Inicializa o carrinho
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Remover produto do carrinho
if(isset($_GET['remove'])){
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: carrinho.php");
    exit;
}

$total = 0;
$produtos = [];

// Carregar produtos do carrinho do banco
if(!empty($_SESSION['cart'])){
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $produtosDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Reindexa pelo ID para facilitar acesso
    foreach($produtosDB as $p){
        $produtos[$p['id']] = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrinho de Compras</title>
<link rel="stylesheet" href="css/carrinho.css">
</head>
<body>

<header>
  <img src="img/logo.jpg" alt="logo" width="100">
</header>

<main class="carrinho-container">
  <h2>Carrinho de Compras</h2>

  <?php if(empty($_SESSION['cart'])): ?>
    <p style="text-align:center; font-size:1.2rem;">Seu carrinho está vazio</p>
    <div style="text-align:center;">
      <a href="index.php" class="link-carrinho-voltar">← Continuar Comprando</a>
    </div>

  <?php else: ?>
    <table class="carrinho-table">
      <tr>
        <th>Produto</th>
        <th>Imagem</th>
        <th>Preço</th>
        <th>Quantidade</th>
        <th>Subtotal</th>
        <th>Ação</th>
      </tr>

      <?php foreach($_SESSION['cart'] as $id => $qtd): 
        if(!isset($produtos[$id])) continue; // Pula se o produto não existir no DB
        $produto = $produtos[$id];
        $subtotal = $produto['preco'] * $qtd;
        $total += $subtotal;
      ?>
      <tr>
        <td><strong><?php echo htmlspecialchars($produto['nome']); ?></strong></td>
        <td><img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" width="80"></td>
        <td>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></td>
        <td><?php echo $qtd; ?></td>
        <td>R$ <?php echo number_format($subtotal,2,',','.'); ?></td>
        <td><a href="carrinho.php?remove=<?php echo $id; ?>">Remover</a></td>
      </tr>
      <?php endforeach; ?>

      <tr class="total-row">
        <td colspan="4" style="text-align:center;">Total:</td>
        <td colspan="2" style="text-align:center;">R$ <?php echo number_format($total,2,',','.'); ?></td>
      </tr>
    </table>

    <div class="buttons">
      <a href="index.php" class="carrinho-voltar">Voltar</a>
      <a href="compra.php" class="carrinho-compra">Realizar compra</a>
    </div>

  <?php endif; ?>
</main>
    
<footer style="margin-top:2rem;">&copy; 2025 RD Modas — Todos os direitos reservados.</footer>

</body>
</html>
