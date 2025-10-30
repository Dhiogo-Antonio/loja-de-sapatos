<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirecionar para a página de login
    exit;
}

// Lógica de compra aqui (por exemplo, processar pagamento)

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Finalizar Compra</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <img src="img/logo.jpg" alt="logo" width="100">
  <nav>
    <a href="index.php">Início</a>
  </nav>
</header>

<main class="compra-container">
  <h2>Finalizar Compra</h2>

  <p>Obrigado por comprar conosco, <?php echo $_SESSION['user']; ?>! Seu pedido está sendo processado.</p>

  <div style="text-align:center; margin-top:2rem;">
    <a href="index.php" class="link-carrinho-voltar">Voltar para a página inicial</a>
  </div>

</main>

<footer style="margin-top:2rem;">&copy; 2025 RD Modas — Todos os direitos reservados.</footer>

</body>
</html>
