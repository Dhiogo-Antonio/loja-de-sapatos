<?php
require_once "C:/Turma1/xampp/htdocs/loja-de-sapatos/shoe-store/backend/models/UsuarioModel.php";

class UsuarioController {
    private $usuarioModel;

    public function __construct($pdo) {
        $this->usuarioModel = new UsuarioModel($pdo);
    }

    public function buscarPorEmaileSenha($email, $senha) {
        return $this->usuarioModel->buscarPorEmaileSenha($email, $senha);
    }

    public function cadastrar($nome, $email, $senha) {
        return $this->usuarioModel->cadastrar($nome, $email, $senha);
    }

    
    public function login($email, $senha) {
        $usuario = $this->usuarioModel->buscarPorEmaileSenha($email, $senha);

        if ($usuario) {
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $email = $_POST['email'];
            $senha = $_POST['senha'];

            header("Location: ../frontend/index.php");
            exit;
        } else {
            return "❌ E-mail ou senha incorretos.";
        }
    }
}
?>
