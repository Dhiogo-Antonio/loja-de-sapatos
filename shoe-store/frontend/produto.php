<?php
session_start();

// Inicializa carrinho se não existir
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Se veio via GET para adicionar produto
if(isset($_GET['add'])){
    $id = (int)$_GET['add'];
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id] += 1; // aumenta quantidade
    } else {
        $_SESSION['cart'][$id] = 1; // adiciona produto
    }
    header("Location: index.php"); // redireciona para evitar refresh
    exit;
}
?>




<?php
$produtos = [
    ["nome"=>"Nike Air Max Tn","descricao"=>"Conforto, amortecimento, estilo moderno","preco"=>749.90,"imagem"=>"img/nike-airmax.webp"],
    ["nome"=>"Campus Adidas","descricao"=>"Estilo clássico e confortável","preco"=>399.90,"imagem"=>"img/addidas-campus.webp"],
    ["nome"=>"Nike UltraRun","descricao"=>"Estilo urbano, casual e confortável","preco"=>99.90,"imagem"=>"img/adidas-UltraRun.avif"],
];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produto = $produtos[$id] ?? null;

if(!$produto){
    echo "<p>Produto não encontrado!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $produto['nome']; ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <a href="index.php">← Voltar</a>
</header>

<main style="text-align:center; padding:2rem;">
  <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" style="width:300px; border-radius:12px;">
  <h2><?php echo $produto['nome']; ?></h2>
  <p><?php echo $produto['descricao']; ?></p>
  <strong>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></strong><br><br>
  <button onclick="addToCart(<?php echo $id; ?>)">Adicionar ao Carrinho</button>
</main>

<script src="js/main.js"></script>
</body>
</html>
