<?php
session_start();
include('database.php'); // Conexão com o banco de dados

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome']; // Pegando o nome do usuário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar se o e-mail já está cadastrado
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Usuário já existe
        $erro = "Este e-mail já está cadastrado.";
    } else {
        // Inserir o novo usuário no banco
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha); // Senha em texto simples
        $stmt->execute();

        // Redirecionar para a página de login
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <img src="img/logo.jpg" alt="logo" width="100">
    </header>

    <main class="login-container">
        <h2>Cadastre-se</h2>

        <?php if (isset($erro)): ?>
            <p class="msg-erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="nome" placeholder="Nome completo" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit" name="cadastrar">Cadastrar</button>
        </form>

        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </main>

    <footer>&copy; 2025 RD Modas — Todos os direitos reservados.</footer>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro - RD Modas</title>
<link rel="stylesheet" href="css/login.css">
</head>
<body>

<header>
  <img src="img/logo.jpg" alt="logo" width="100">
  <h1>RD MODAS</h1>
</header>

<main>
  <div class="login-container">
    <h2>Criar Conta</h2>
    <?php if ($msg): ?><div class="msg-erro"><?= $msg ?></div><?php endif; ?>
    <form method="POST">
      <input type="text" name="nome" placeholder="Nome completo" required>
      <input type="email" name="email" placeholder="E-mail" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <input type="password" name="confirmar" placeholder="Confirmar senha" required>
      <button type="submit">Cadastrar</button>
    </form>
    <p>Já tem conta? <a href="login.php">Entrar</a></p>
  </div>
</main>

<footer>&copy; 2025 RD Modas — Todos os direitos reservados.</footer>
</body>
</html>
