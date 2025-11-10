<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php'); // Conexão com o banco de dados

if (isset($_POST['cadastrar'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $telefone = trim($_POST['telefone']); // ← novo campo

    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, telefone) VALUES (:nome, :email, :senha, :telefone)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha); 
        $stmt->bindParam(':telefone', $telefone);
        $stmt->execute();

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
    <link rel="stylesheet" href="css/login.css">
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
            <input type="text" name="nome" placeholder="Nome completo" required class="input">
            <input type="email" name="email" placeholder="E-mail" required class="input">
            <input type="tel" name="telefone" placeholder="Telefone" pattern="\(\d{2}\)\s\d{5}-\d{4}"  required class="input">
            <input type="password" name="senha" placeholder="Senha" required class="input">
            <button type="submit" name="cadastrar">Cadastrar</button>
        </form>

        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </main>

    <footer>&copy; 2025 RD Modas — Todos os direitos reservados.</footer>
</body>
</html>
