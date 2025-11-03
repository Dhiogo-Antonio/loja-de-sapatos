<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Atualiza a foto do usuário
if (isset($_POST['upload'])) {
    $userId = $_SESSION['user_id'];
    $foto = $_FILES['foto'];

    if ($foto['error'] === 0) {
        $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $permitidas)) {
            $novoNome = "user_" . $userId . "." . $ext;
            $pasta = "uploads/";
            if (!is_dir($pasta)) mkdir($pasta, 0755, true);

            $caminho = $pasta . $novoNome;
            move_uploaded_file($foto['tmp_name'], $caminho);

            $stmt = $pdo->prepare("UPDATE usuarios SET foto = :foto WHERE id = :id");
            $stmt->execute([':foto' => $caminho, ':id' => $userId]);


        } 
    }

    header("Location: conta.php");
    exit;
}

// Busca os dados do usuário logado
$stmt = $pdo->prepare("SELECT nome, email, foto FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Minha Conta - RD Modas</title>
<link rel="stylesheet" href="css/conta.css">

</head>
<body>
<header>
  <img src="img/logo.jpg" alt="logo" width="100">

  <h2>Minha Conta</h2>
  
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

   
  </nav>
</header>

<main class="conta-container">
  

  <?php if(isset($_SESSION['mensagem'])): ?>
    <p class="msg"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></p>
  <?php endif; ?>

  <div class="foto-perfil">
    <img src="<?php echo !empty($usuario['foto']) ? $usuario['foto'] : 'img/user_default.png'; ?>" alt="Foto de Perfil">
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="foto" accept="image/*" required>
      <button type="submit" name="upload">Atualizar Foto</button>
    </form>
  </div>

  <div class="conta-info">
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
   
  </div>

  <div class="conta-actions">
    <a href="index.php" class="carrinho-link">Voltar</a>
    <a href="historico.php">Histórico de Compras</a>
    <a href="logout.php">Sair</a>
  </div>
</main>

<footer style="text-align:center; margin-top:2rem;">
  &copy; 2025 RD Modas — Todos os direitos reservados.
</footer>
</body>
</html>
<link rel="stylesheet" href="js/main.js">
<script>
  const menu = document.getElementById('menu');
  const navList = document.getElementById('navList');

  menu.addEventListener('click', () => {
    menu.classList.toggle('active');
    navList.classList.toggle('open');
    document.body.classList.toggle('menu-open');
  });
</script>
