<?php
session_start();
include('C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/config/database.php');


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


if (empty($_SESSION['cart'])) {
    $_SESSION['notif'] = "Seu carrinho está vazio!";
    header("Location: index.php");
    exit;
}


foreach($_SESSION['cart'] as $id => $quantidade){
    
    $stmt = $pdo->prepare("SELECT estoque FROM produtos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produtosDB = $stmt->fetch(PDO::FETCH_ASSOC);

    if($produtosDB['estoque'] < $quantidade){
        $_SESSION['notif'] = "Estoque insuficiente para algum produto!";
        header("Location: carrinho.php");
        exit;
    }

    
    $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque - :quantidade WHERE id = :id");
    $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}


unset($_SESSION['cart']);  


$_SESSION['notif'] = "Compra realizada com sucesso!";


header("Location: finalizar-compra.php");
exit;
?>
