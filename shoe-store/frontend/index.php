<?php
session_start();



include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

$stmt = $pdo->query("SELECT * FROM produtos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);



if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

   

if(isset($_GET['add'])){
    $id = (int)$_GET['add'];
    if(isset($produtos[$id])){
        if(isset($_SESSION['cart'][$id])){
            $_SESSION['cart'][$id] += 1;
        } else {
            $_SESSION['cart'][$id] = 1;
        }

        $_SESSION['notif'] = "Produto adicionado ao carrinho!";
    }
    header("Location: index.php");
    exit;
}



$q = isset($_GET['q']) ? trim($_GET['q']) : '';


if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE nome LIKE :q OR descricao LIKE :q");
    $like = "%$q%";
    $stmt->bindParam(':q', $like, PDO::PARAM_STR);
    $stmt->execute();
} else {
    
    $stmt = $pdo->query("SELECT * FROM produtos");
}

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);



$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

if ($categoria !== '') {
    if ($categoria == 'tenis-chinelos') {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE categoria IN ('Tênis', 'Chinelos')");
    } elseif ($categoria == 'roupas-masculinas') {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE categoria = 'Roupas Masculinas'");
    } elseif ($categoria == 'roupas-femininas') {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE categoria = 'Roupas Femininas'");
    } elseif ($categoria == 'bolsas') {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE categoria = 'Bolsas'");
    }  elseif ($categoria == 'unissex') {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE categoria = 'Unissex'");
    }
    $stmt->execute();
} elseif ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE nome LIKE :q OR descricao LIKE :q");
    $like = "%$q%";
    $stmt->bindParam(':q', $like, PDO::PARAM_STR);
    $stmt->execute();
} else {
    $stmt = $pdo->query("SELECT * FROM produtos");
}

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);




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
  <img src="img/logo.jpg" alt="logo" width="100" class="img-logo">

  
  <div class="search-container">
    <form method="GET" action="index.php">
      <input type="text" name="q" placeholder="Buscar tênis..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
      
    </form>
  </div>



 <nav class="navbar">

   
    <div class="menu" id="menu">
      <span></span>
      <span></span>
      <span></span>
    </div>

   
    <ul class="nav-list" id="navList">
      <li><a class="a" href="index.php">Início</a></li>
      <li><a class="a" href="carrinho.php">Carrinho</a></li>
      <li><a class="a" href="conta.php">Conta</a></li>
      <li><a class="a" href="logout.php">Sair</a></li>
    </ul>

   
    <a href="carrinho.php" class="carrinho-link">
      <img src="./img/icon-cart.webp" alt="Carrinho" width="40" class="img-icon">
      (<?php echo array_sum($_SESSION['cart']); ?>)
    </a>

    <a href="conta.php" class="conta-link">
      <img src="./img/conta.png" alt="conta" width="80" class="img-icon">
    </a>
    </a>
  </nav>

</header>

<main>

<ul>
  <li><a href="index.php?categoria=tenis-chinelos" class="<?php echo ($categoria == 'tenis-chinelos') ? 'active' : ''; ?>">Tênis/Chinelos</a></li>
  <li><a href="index.php?categoria=roupas-masculinas" class="<?php echo ($categoria == 'roupas-masculinas') ? 'active' : ''; ?>">Roupas Masculinas</a></li>
  <li><a href="index.php?categoria=roupas-femininas" class="<?php echo ($categoria == 'roupas-femininas') ? 'active' : ''; ?>">Roupas Femininas</a></li>
  <li><a href="index.php?categoria=bolsas" class="<?php echo ($categoria == 'bolsas') ? 'active' : ''; ?>">Bolsas</a></li>
  <li><a href="index.php?categoria=unissex" class="<?php echo ($categoria == 'unissex') ? 'active' : ''; ?>">Roupas Unissex</a></li>
</ul><br>


 <hr>


  <div class="container">
    <?php foreach($produtos as $key => $produto): ?>
      <div class="product-card">
        <a href="produto.php?id=<?php echo $produto['id']; ?>">
          <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" />
        </a>
        <h3><?php echo $produto['nome']; ?></h3><br><br>
        <strong>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></strong><br>
        <a href="index.php?add=<?php echo $produto['id']; ?>">

          <button>Adicionar ao Carrinho</button>
        </a>
        
      </div>
    <?php endforeach; ?>
  </div>
  
</main>
<footer style="margin-top:2rem;">&copy; 2025 RD Modas — Todos os direitos reservados.</footer>
</body>
</html>

<script>
  const menu = document.getElementById('menu');
  const navList = document.getElementById('navList');

  menu.addEventListener('click', () => {
    menu.classList.toggle('active');
    navList.classList.toggle('open');
    document.body.classList.toggle('menu-open');
  });

  
  const cards = document.querySelectorAll('.product-card');

  const observer = new IntersectionObserver(entries => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        entry.target.style.animation = `fadeInUp 0.6s ease forwards ${index * 0.1}s`;
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  cards.forEach(card => observer.observe(card));


</script>


