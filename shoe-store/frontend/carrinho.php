<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirecionar para a página de login
    exit;
}

$produtos = [
    ["nome"=>"Nike Air Max Tn","descricao"=>"Conforto, amortecimento, estilo moderno","preco"=>749.90,"imagem"=>"img/nike-airmax.webp"],
    ["nome"=>"Campus Adidas","descricao"=>"Estilo clássico e confortável","preco"=>399.90,"imagem"=>"img/addidas-campus.webp"],
    ["nome"=>"Nike UltraRun","descricao"=>"Estilo urbano, casual e confortável","preco"=>99.90,"imagem"=>"img/adidas-UltraRun.avif"],
];

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_GET['remove'])){
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: carrinho.php");
    exit;
}

$total = 0;
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
  <nav>
    <a href="index.php">Início</a>
  </nav>
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
        $produto = $produtos[$id];
        $subtotal = $produto['preco'] * $qtd;
        $total += $subtotal;
      ?>
      <tr>
        <td><strong><?php echo $produto['nome']; ?></strong></td>
        <td><img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>"></td>
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

    <div style="text-align:center; margin-top:2rem;">
      <a href="compra.php" class="carrinho-compra">Realizar compra</a>
    </div>

  <?php endif; ?>
</main>
    
<footer style="margin-top:2rem;">&copy; 2025 RD Modas — Todos os direitos reservados.</footer>

</body>
</html>
