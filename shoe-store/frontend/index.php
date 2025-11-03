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
      <li><a class="a" href="#">Início</a></li>
      <li><a class="a" href="carrinho.php">Carrinho</a></li>
      <li><a class="a" href="contato.php">Contato</a></li>
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
<section class="banner">
  <img src="img/banner.webp" alt="banner" class="banner-img">

  <div class="arrows">
    <button class="button-seta">
      <img src="img/seta.png" alt="seta esquerda">
    </button>
    <button class="button-seta">
      <img src="img/seta.png" alt="seta direita">
    </button>
  </div>

  <div class="indicators">
    <ul>
      <li class="active"></li>
      <li></li>
      <li></li>
    </ul>
  </div>
</section>


  <h2 class="title">Nossos Tênis</h2>
  <div class="container">
    <?php foreach($produtos as $key => $produto): ?>
      <div class="product-card">
        <a href="produto.php?id=<?php echo $produto['id']; ?>">
          <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" />
        </a>
        <h3><?php echo $produto['nome']; ?></h3>
        <p><?php echo $produto['descricao']; ?></p>
        <strong>R$ <?php echo number_format($produto['preco'],2,',','.'); ?></strong><br>
        <a href="index.php?add=<?php echo $produto['id']; ?>">

          <button>Adicionar ao Carrinho</button>
        </a>
        
      </div>
    <?php endforeach; ?>
  </div>
  <footer style="margin-top:2rem;">&copy; 2025 RD Modas — Todos os direitos reservados.</footer>
</main>
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



const bannerImg = document.querySelector('.banner-img');
const arrows = document.querySelectorAll('.button-seta');
const indicators = document.querySelectorAll('.indicators ul li');


const banners = [
  'img/banner.webp',
  'img/banner2.webp',
  'img/banner3.png'
];

let currentIndex = 0;

function updateBanner() {
  
  bannerImg.style.opacity = 0;
  setTimeout(() => {
    bannerImg.src = banners[currentIndex];
    bannerImg.style.opacity = 1;
  }, 250);

  
  indicators.forEach((li, i) => {
    li.classList.toggle('active', i === currentIndex);
  });
}


arrows[0].addEventListener('click', () => {
  currentIndex = (currentIndex - 1 + banners.length) % banners.length;
  updateBanner();
});

arrows[1].addEventListener('click', () => {
  currentIndex = (currentIndex + 1) % banners.length;
  updateBanner();
});


indicators.forEach((li, i) => {
  li.addEventListener('click', () => {
    currentIndex = i;
    updateBanner();
  });
});
</script>


