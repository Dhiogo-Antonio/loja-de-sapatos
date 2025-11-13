<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_GET['add'])){
    $id = (int)$_GET['add'];
    $quantity = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;

    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$produto){
        echo "<p>Produto não encontrado!</p>";
        exit;
    }

    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id] += $quantity;
    } else {
        $_SESSION['cart'][$id] = $quantity;
    }

    header("Location: index.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$produto){
    echo "<p>Produto não encontrado!</p>";
    exit;
}

// Galeria de imagens (exemplo: imagem principal + extras)
$imagens = [$produto['imagem']];
if(!empty($produto['imagem2'])) $imagens[] = $produto['imagem2'];
if(!empty($produto['imagem3'])) $imagens[] = $produto['imagem3'];

$categoria = $produto['categoria'];
$stmtSug = $pdo->prepare("SELECT * FROM produtos WHERE categoria = :cat AND id != :id ORDER BY RAND() LIMIT 9");
$stmtSug->execute([':cat' => $categoria, ':id' => $id]);
$sugestoes = $stmtSug->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($produto['nome']); ?></title>
<link rel="stylesheet" href="css/produto.css">
<style>

</style>
</head>
<body>

<header>
  <img src="img/logo.jpg" alt="logo" width="100">
</header>

<nav class="navbar">

   
    <div class="menu" id="menu">
      <span></span>
      <span></span>
      <span></span>
    </div>

   
    <ul class="nav-list" id="navList">
      <li><a class="a" href="index.php">Início</a></li>
      <li><a class="a" href="carrinho.php">Carrinho</a></li>
      <li><a class="a" href="contato.php">Contato</a></li>
      <li><a class="a" href="logout.php">Sair</a></li>
    </ul>

  
  </nav>

<main>
  <div class="gallery">
    <img id="mainImage" src="<?php echo htmlspecialchars($imagens[0]); ?>" class="gallery-main">
    <div class="gallery-thumbs">
      <?php foreach($imagens as $index => $img): ?>
        <img src="<?php echo htmlspecialchars($img); ?>" class="<?php echo $index===0?'active':''; ?>" onclick="changeImage(this)">
      <?php endforeach; ?>
    </div>
  </div>

  <div class="product-info">
    <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
    <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
    <strong>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></strong>

    <form method="get">
  <input type="hidden" name="add" value="<?php echo $produto['id']; ?>">

  <div class="size-container">
    <label for="size">Tamanho:</label>
    <select name="size" id="size" required>
      <?php for($t=34; $t<=44; $t++): ?>
        <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="quantity-container">
    <label for="qty">Quantidade:</label>
    <input type="number" name="qty" value="1" min="1">
  </div>

  <button type="submit" class="add-cart">Adicionar ao Carrinho</button>
</form>

    <div class="specs">
      <h3>Especificações:</h3>
      <ul>
        <li>Material: Couro sintético</li>
        <li>Sola: Borracha antiderrapante</li>
        <li>Disponível nos tamanhos 34 a 44</li>
      </ul>

     
    </div>
 
    <a href="index.php" class="btn-voltar">←</a>
  </div>



  
</main>
  
  <?php if($sugestoes): ?>
  <div class="sugestoes">
    <h3>Você também pode gostar</h3>
    <div class="sugestoes-container">
      <?php foreach($sugestoes as $s): ?>
        <div class="sugestoes-item">
          <img src="<?php echo htmlspecialchars($s['imagem']); ?>" alt="<?php echo htmlspecialchars($s['nome']); ?>">
          <h4><?php echo htmlspecialchars($s['nome']); ?></h4>
          <strong>R$ <?php echo number_format($s['preco'],2,',','.'); ?></strong>
          <a href="produto.php?id=<?php echo $s['id']; ?>">Ver Produto</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div><br><br>
  <?php endif; ?>


<script>
function changeImage(img){
  document.getElementById('mainImage').src = img.src;
  document.querySelectorAll('.gallery-thumbs img').forEach(el => el.classList.remove('active'));
  img.classList.add('active');
}

  const menu = document.getElementById('menu');
  const navList = document.getElementById('navList');

  menu.addEventListener('click', () => {
    menu.classList.toggle('active');
    navList.classList.toggle('open');
    document.body.classList.toggle('menu-open');
  });

</script>

</body>
</html>
