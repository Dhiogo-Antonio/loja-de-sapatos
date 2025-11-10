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

            $_SESSION['mensagem'] = "Foto atualizada com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Formato de imagem inválido. Use JPG, PNG ou WEBP.";
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao enviar o arquivo.";
    }

    header("Location: conta.php");
    exit;
}

// Busca os dados do usuário logado
$stmt = $pdo->prepare("SELECT nome, email, telefone, criado_em FROM usuarios WHERE id = :id");
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
      <span></span><span></span><span></span>
    </div>
    <ul class="nav-list" id="navList">
      <li><a class="a" href="index.php">Início</a></li>
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

  <section class="perfil-card">
    <div class="foto-perfil">
      <img src="<?php echo !empty($usuario['foto']) ? $usuario['foto'] : 'img/user_default.png'; ?>" alt="Foto de Perfil">
      <form method="POST" enctype="multipart/form-data">
        
      </form>
    </div>

    <div class="conta-info">
      <h3>Informações Pessoais</h3>
      <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
      <p><strong>Telefone:</strong> <?php echo htmlspecialchars($usuario['telefone']); ?></p>
      <p><strong>Data de Cadastro:</strong> <?php echo date("d/m/Y", strtotime($usuario['criado_em'])); ?></p>
      
    </div>
  </section>

  <section class="conta-actions">
    <h3>Minhas Ações</h3>
    <div class="botoes">
      <a href="historico.php">🛍️ Histórico de Compras</a>
      <a href="favoritos.php">❤️ Meus Favoritos</a>
      <a href="index.php">🏠 Voltar à Loja</a>
      <a href="logout.php" class="logout-btn">🚪 Sair</a>
    </div>
  </section>

</main>

<footer style="text-align:center; margin-top:2rem;">
  &copy; 2025 RD Modas — Todos os direitos reservados.
</footer>

<script>
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
