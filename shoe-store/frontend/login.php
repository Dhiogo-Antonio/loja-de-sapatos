<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $_SESSION['user_id'] = $user['id']; 
$_SESSION['cart'] = !empty($user['carrinho']) ? json_decode($user['carrinho'], true) : [];

    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && $usuario['senha'] === $senha) {
        
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_nome'] = $usuario['nome'];
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['user'] = $usuario['nome'];

        header("Location: conta.php"); 
        exit;
    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}




?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <header>
        <img src="img/logo.jpg" alt="logo" width="100">
    </header>

    <main class="login-container">
        <h2>Entrar na sua conta</h2>

        <?php if(isset($erro)): ?>
            <p class="msg-erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="email" placeholder="Usuário" required class="input">
            <input type="password" name="senha" placeholder="Senha" required class="input">
            <button type="submit" name="login">Entrar</button>

            <a href="#">Esqueci a senha</a><br>
        </form>

        <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
    </main>

    <footer>&copy; 2025 RD Modas — Todos os direitos reservados.</footer>
</body>
</html>
