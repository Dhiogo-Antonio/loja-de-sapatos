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

            
        } else {
            $_SESSION['mensagem'] = "⚠️ Formato inválido. Use JPG, PNG ou WEBP.";
        }
    } else {
        $_SESSION['mensagem'] = "❌ Erro ao enviar o arquivo.";
    }

    header("Location: conta.php");
    exit;
}

// Busca os dados do usuário logado
$stmt = $pdo->prepare("SELECT nome, email, telefone, criado_em, foto FROM usuarios WHERE id = :id");
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
<header class="top-header">
  <img src="img/logo.jpg" alt="logo" class="logo">
  
</header>
<h1>Sua Conta</h1>

<main class="conta-container">

  <section class="perfil-card">
    <div class="foto-perfil">
      <div class="foto-wrapper">
        <img src="<?php echo !empty($usuario['foto']) ? $usuario['foto'] : 'img/user_default.png'; ?>" alt="Foto de Perfil">
        <form method="POST" enctype="multipart/form-data">
          <label for="foto" class="btn-upload">📷 Alterar Foto</label>
          <input type="file" name="foto" id="foto" accept="image/*" onchange="this.form.submit()">
          <input type="hidden" name="upload" value="1">
        </form>
      </div>
    </div>

    <div class="conta-info">
      <h3>Suas Informações</h3>
      <div class="info-grid">
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($usuario['telefone']); ?></p>
        <p><strong>Data de Cadastro:</strong> <?php echo date("d/m/Y", strtotime($usuario['criado_em'])); ?></p>
      </div>
    </div>
  </section>

  <hr>

  <section class="conta-actions">
    <h3>⚙️ Opções da Conta</h3>
    <div class="botoes">
      <a href="historico.php" class="btn"> Histórico de Compras</a>
      <a href="index.php" class="btn"> Voltar à Loja</a>
      <a href="logout.php" class="btn logout-btn">Sair</a>
    </div>
  </section>

</main>



</body>
</html>
