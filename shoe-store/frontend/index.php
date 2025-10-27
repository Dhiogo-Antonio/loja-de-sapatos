<?php
session_start();

// Array de produtos
$produtos = [
    ["nome"=>"Nike Air Max Tn","descricao"=>"Conforto, amortecimento, estilo moderno","preco"=>749.90,"imagem"=>"img/267436-800-800.webp"],
    ["nome"=>"Campus Adidas","descricao"=>"Estilo clássico e confortável","preco"=>399.90,"imagem"=>"img/addidas-campus.webp"],
    ["nome"=>"Vans Old Skool","descricao"=>"Estilo urbano, casual e confortável","preco"=>299.90,"imagem"=>"img/vans-placeholder.jpg"],
];

// Inicializa carrinho se não existir
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Adicionar produto
if(isset($_GET['add'])){
    $id = (int)$_GET['add'];
    if(isset($produtos[$id])){
        if(isset($_SESSION['cart'][$id])){
            $_SESSION['cart'][$id] += 1;
        } else {
            $_SESSION['cart'][$id] = 1;
        }
    }
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loja de Sapatos</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <img src="img/logo.jpg" alt="logo" width="100">
  <nav>
    <a href="index.php">Início</a>
    <a href="carrinho.php" class="carrinho-link">🛒 Carrinho (<?php echo array_sum($_SESSION['cart']); ?>)</a>
  </nav>
</header>

<main>
  <section class="banner">
    <img src="img/banner.webp" alt="banner" class="banner-img">
  </section>

  <h2 class="title">Nossos Tênis</h2>
  <div class="container">
    <?php foreach($produtos as $key => $produto): ?>
      <div class="product-card">
        <a href="produto.php?id=<?php echo $key; ?>">
          <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" />
        </a>
        <h3><?php echo $produto['nome']; ?></h3>
        <p><?php echo $produto['descricao']; ?></p>
        <strong>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></strong><br>
        <a href="index.php?add=<?php echo $key; ?>">
          <button>Adicionar ao Carrinho</button>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
  <footer style="margin-top:2rem;">&copy; 2025 RD Modas</footer>
</main>
</body>
</html>
