<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

// Verifica se é admin
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ====== EDITAR USUÁRIO ======
if (isset($_POST['editar_usuario'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];

    $sql = "UPDATE usuarios SET nome=?, email=?, tipo=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $tipo, $id]);
}

// ====== EXCLUIR USUÁRIO ======
if (isset($_POST['excluir_usuario'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->execute([$id]);
}

// ====== BUSCAR USUÁRIOS ======
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerenciar Usuários</title>
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
  background: #f8f8f8;
}

td button {
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

td button:hover {
  background: #333;
}

form input, form select {
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 5px;
  width: 100%;
  font-size: 14px;
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
    
    <nav>
      <a href="index.php">📊 Dashboard</a>
      <a href="usuarios.php" class="active">👥 Usuários</a>
      <a href="produtos.php">👟 Produtos</a>
      <a href="pedidos.php">📦 Pedidos</a>
      <a href="logout.php" class="logout">🚪 Sair</a>
    </nav>
  </aside>

  <main class="content">
    <h1>Gerenciar Usuários</h1>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u): ?>
          <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nome']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['tipo']) ?></td>
            <td>
              <!-- Formulário de edição -->
              <form method="POST">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <input type="text" name="nome" value="<?= htmlspecialchars($u['nome']) ?>" required>
                <input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required>
                <select name="tipo" required>
                  <option value="cliente" <?= $u['tipo'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                  <option value="admin" <?= $u['tipo'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <button type="submit" name="editar_usuario">Salvar</button>
              </form>

              <!-- Formulário de exclusão -->
              <form method="POST" style="margin-top:8px;">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button type="submit" name="excluir_usuario" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
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
