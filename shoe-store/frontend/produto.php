<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

// Inicializa o carrinho
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Adicionar ao carrinho
if(isset($_GET['add'])){
    $id = (int)$_GET['add'];

    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$produto){
        echo "<p>Produto não encontrado!</p>";
        exit;
    }

    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id] += 1;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    header("Location: produto.php?id=$id");
    exit;
}

// Pega o ID do produto a ser exibido
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

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
<title><?php echo htmlspecialchars($produto['nome']); ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <a href="index.php">← Voltar</a>
  <a href="carrinho.php">Carrinho (<?php echo array_sum($_SESSION['cart']); ?>)</a>
</header>

<main style="display:flex; justify-content:center; padding:2rem;">
  <div class="produto-card" style="border:1px solid #ccc; padding:2rem; width:300px; text-align:center; border-radius:12px;">
    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" style="width:100%; height:250px; object-fit:cover; border-radius:12px;">
    <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
    <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
    <strong>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></strong><br><br>
    <a href="?add=<?php echo $produto['id']; ?>"><button>Adicionar ao Carrinho</button></a>
  </div>
</main>

<footer style="text-align:center; margin-top:2rem;">&copy; 2025 RD Modas — Todos os direitos reservados.</footer>

</body>
</html>
