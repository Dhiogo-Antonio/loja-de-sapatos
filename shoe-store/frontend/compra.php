<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

// Verifica se usuário está logado
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Verifica se há produtos no carrinho
if (empty($_SESSION['cart'])) {
    $_SESSION['notif'] = "Seu carrinho está vazio!";
    header("Location: index.php");
    exit;
}

// Processa cada produto no carrinho
foreach($_SESSION['cart'] as $id => $quantidade){
    // Verifica se há estoque suficiente
    $stmt = $pdo->prepare("SELECT estoque FROM produtos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produtosDB = $stmt->fetch(PDO::FETCH_ASSOC);

    if($produtosDB['estoque'] < $quantidade){
        $_SESSION['notif'] = "Estoque insuficiente para algum produto!";
        header("Location: carrinho.php");
        exit;
    }

    // Atualiza o estoque
    $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque - :quantidade WHERE id = :id");
    $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

// Limpa o carrinho após a compra
unset($_SESSION['cart']);  

// Mensagem de sucesso
$_SESSION['notif'] = "Compra realizada com sucesso!";

// Redireciona para a página inicial
header("Location: index.php");
exit;
?>
