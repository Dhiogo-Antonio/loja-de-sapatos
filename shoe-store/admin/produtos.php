<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

// Verifica se é admin
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ====== EDITAR PRODUTO ======
if (isset($_POST['editar_produto'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $imagem = $_POST['imagem'];
    $estoque = $_POST['estoque'];

    $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, estoque=?, imagem=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $descricao, $preco, $estoque, $imagem, $id]);

    header("Location: produtos.php#id".$id);
   exit;
}


if (isset($_POST['excluir_produto'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id=?");
    $stmt->execute([$id]);
}


$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerenciar Produtos</title>
<link rel="stylesheet" href="admin.css">
<style>
/* ====== Estilo das Tabelas ====== */
.table-container {
  background: #fff;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  margin-top: 30px;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
  font-size: 15px;
}

th {
  background: #000;
  color: white;
  text-align: left;
  padding: 12px 15px;
  border-bottom: 3px solid #000;
}

td {
  padding: 12px 15px;
  border-bottom: 1px solid #ddd;
  color: #333;
  vertical-align: middle;
}

tr:hover {
  background: #f0f0f0ff;
}

td button, td a {
  background: #000;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 6px 12px;
  text-decoration: none;
  cursor: pointer;
  font-size: 14px;
  transition: 0.3s;
}

td button:hover, td a:hover {
  background: #333;
}

form input {
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 5px;
  width: 100%;
  font-size: 14px;
}

form input[type="number"] {
  width: 80px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 5px;
}
</style>
</head>

<body>
<div class="admin-container">
  <aside class="sidebar">

   <img src="../frontend/img/logo.jpg" alt="logo" width="100" class="img-logo">
    
    <h2>🛒 RD Modas</h2>
    <nav>
      <a href="index.php">📊 Dashboard</a>
      <a href="usuarios.php">👥 Usuários</a>
      <a href="produtos.php" class="active">👟 Produtos</a>
      <a href="pedidos.php">📦 Pedidos</a>
      <a href="logout.php" class="logout">🚪 Sair</a>
    </nav>
  </aside>

  <main class="content">
    <h1>Gerenciar Produtos</h1>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produtos as $p): ?>
          <tr id="id<?= $p['id'] ?>">

            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td><?= htmlspecialchars($p['descricao']) ?></td>
            <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
            <td><?= $p['estoque'] ?></td>
            <td>
              <!-- Formulário de Edição -->
              <form method="POST">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <input type="text" name="nome" value="<?= htmlspecialchars($p['nome']) ?>" required>
                <input type="text" name="descricao" value="<?= htmlspecialchars($p['descricao']) ?>" required>
                <input type="text" name="imagem" placeholder="URL da Imagem"
           value="<?= isset($p['imagem']) ? htmlspecialchars($p['imagem']) : '' ?>">
                <input type="number" step="0.01" name="preco" value="<?= $p['preco'] ?>" required>
                <input type="number" name="estoque" value="<?= $p['estoque'] ?>" required>
                <button type="submit" name="editar_produto">Salvar</button>
              </form>
              <!-- Botão de exclusão -->
              <form method="POST" style="margin-top:8px;">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" name="excluir_produto" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
