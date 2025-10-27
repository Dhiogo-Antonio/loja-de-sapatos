<?php
session_start();

// Array de produtos (mesmo do index.php)
$produtos = [
    ["nome"=>"Nike Air Max Tn","descricao"=>"Conforto, amortecimento, estilo moderno","preco"=>749.90,"imagem"=>"img/267436-800-800.webp"],
    ["nome"=>"Campus Adidas","descricao"=>"Estilo clássico e confortável","preco"=>399.90,"imagem"=>"img/addidas-campus.webp"],
    ["nome"=>"Nike UltraRun","descricao"=>"Estilo urbano, casual e confortável","preco"=>99.90,"imagem"=>"img/adidas-UltraRun.avif"],
];

// Inicializa carrinho se não existir
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Remover item do carrinho
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
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <a href="index.php">← Continuar Comprando</a>
</header>

<main style="padding:2rem;">
  <h2>Carrinho de Compras</h2>

  <?php if(empty($_SESSION['cart'])): ?>
    <p>Seu carrinho está vazio 😢</p>
  <?php else: ?>
    <table style="width:100%; border-collapse: collapse;">
      <tr style="border-bottom:1px solid #ccc;">
        <th>Produto</th>
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
      <tr style="border-bottom:1px solid #ccc;">
        <td><?php echo $produto['nome']; ?></td>
        <td>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></td>
        <td><?php echo $qtd; ?></td>
        <td>R$ <?php echo number_format($subtotal,2,',','.'); ?></td>
        <td>
          <a href="carrinho.php?remove=<?php echo $id; ?>">Remover</a>
        </td>
      </tr>
      <?php endforeach; ?>

      <tr>
        <td colspan="3"><strong>Total:</strong></td>
        <td colspan="2"><strong>R$ <?php echo number_format($total,2,',','.'); ?></strong></td>
      </tr>
    </table>
  <?php endif; ?>
</main>

<footer style="margin-top:2rem;">&copy; 2025 StepUp Store</footer>
</body>
</html>
