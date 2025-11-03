<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');

// Verifica se há itens no carrinho
if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
    $_SESSION['notif'] = "Seu carrinho está vazio!";
    header("Location: index.php");
    exit;
}

// Percorre os produtos do carrinho
foreach($_SESSION['cart'] as $id => $quantidade){
    // Verifica estoque atual
    $stmt = $pdo->prepare("SELECT estoque FROM produtos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if($produto['estoque'] >= $quantidade){
        // Atualiza o estoque no banco
        $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque - :quantidade WHERE id = :id");
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Caso estoque insuficiente
        $_SESSION['notif'] = "Estoque insuficiente para algum produto!";
        header("Location: carrinho.php");
        exit;
    }
}

// Limpa o carrinho após a compra
unset($_SESSION['cart']);
$_SESSION['notif'] = "Compra realizada com sucesso!";
header("Location: index.php");
exit;
?>
