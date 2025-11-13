<?php
 include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/admin/verificarLogin.php'); 
 ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h1>Bem-vindo, <?php echo $_SESSION['admin']; ?>!</h1>

<nav>
  <a href="produtos.php">Gerenciar Produtos</a> |
  <a href="logout.php">Sair</a>
</nav>

</body>
</html>
