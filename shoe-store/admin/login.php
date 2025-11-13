<?php
session_start();
include('../backend/config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($senha, $admin['senha'])) {
        $_SESSION['admin'] = $admin['usuario'];
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login Admin</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h2>Painel Administrativo</h2>
<form method="POST">
  <input type="text" name="usuario" placeholder="Usuário" required><br>
  <input type="password" name="senha" placeholder="Senha" required><br>
  <button type="submit">Entrar</button>
</form>
<?php if(isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
</body>
</html>
